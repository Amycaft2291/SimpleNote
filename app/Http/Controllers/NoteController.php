<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Note;
use App\Models\NoteImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $labels = Label::where('user_id', $userId)
            ->withCount('notes')
            ->orderBy('name')
            ->get();

        $query = Note::where('user_id', $userId)
            ->with(['labels', 'images']);

        if ($request->filled('label')) {
            $query->whereHas('labels', function($q) use ($request) {
                $q->where('labels.id', $request->label);
            });
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }

        $notes = $query->orderByDesc('is_pinned')
            ->orderByDesc('pinned_at')
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard', compact('notes', 'labels'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'      => 'nullable|string|max:255',
            'content'    => 'nullable|string',
            'note_color' => 'nullable|string|in:#ffffff,#fef08a,#bbf7d0,#bfdbfe,#fbcfe8,#fed7aa,#1e293b',
            'labels'     => 'nullable|array',
            'labels.*'   => 'integer|exists:labels,id',
            'images'     => 'nullable|array',
            'images.*'   => 'file|image|max:5120',
        ]);

        // SỬA LỖI: Thêm note_color vào đây để lưu xuống DB
        $note = Note::create([
            'title'      => $validated['title'] ?? '(Không có tiêu đề)',
            'content'    => $validated['content'] ?? '',
            'note_color' => $validated['note_color'] ?? '#ffffff', // Mặc định là màu trắng nếu không chọn
            'user_id'    => Auth::id(),
        ]);

        if (!empty($validated['labels'])) {
            $ids = Label::where('user_id', Auth::id())
                        ->whereIn('id', $validated['labels'])
                        ->pluck('id');
            $note->labels()->sync($ids);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('note_images', 'public');
                NoteImage::create(['note_id' => $note->id, 'image_path' => $path]);
            }
        }

        return redirect()->back()->with('success', 'Đã tạo ghi chú thành công!');
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        if ($note->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'title'      => 'nullable|string|max:255',
            'content'    => 'nullable|string',
            'note_color' => 'nullable|string|in:#ffffff,#fef08a,#bbf7d0,#bfdbfe,#fbcfe8,#fed7aa,#1e293b',
            'labels'     => 'nullable|array',
            'labels.*'   => 'integer|exists:labels,id',
            'images'     => 'nullable|array',        // BỔ SUNG: Validate ảnh khi update
            'images.*'   => 'file|image|max:5120',   // BỔ SUNG: Đảm bảo bảo mật file ảnh
        ]);

        // SỬA LỖI: Thêm note_color vào lệnh update và dùng biến $validated đã sạch
        $note->update([
            'title'      => $validated['title'] ?? '(Không có tiêu đề)',
            'content'    => $validated['content'],
            'note_color' => $validated['note_color'] ?? $note->note_color, // Giữ màu cũ nếu request không truyền
        ]);

        $labelIds = [];
        if (!empty($validated['labels'])) { // Đồng bộ cách viết dùng dữ liệu đã validate cho an toàn
            $labelIds = Label::where('user_id', Auth::id())
                            ->whereIn('id', $validated['labels'])
                            ->pluck('id');
        }
        $note->labels()->sync($labelIds);
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('note_images', 'public');
                $note->images()->create([
                    'image_path' => $path
                ]);
            }
        }
        
        return back()->with('success', 'Đã cập nhật ghi chú!');
    }

    public function deleteImage(\App\Models\NoteImage $image)
    {
        if ($image->note->user_id !== auth()->id()) {
            abort(403);
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image->image_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();
        return back()->with('success', 'Đã xóa ảnh thành công!');
    }

    public function destroy(Note $note): RedirectResponse
    {
        if ($note->user_id !== Auth::id()) abort(403);

        $imagePaths = $note->images->pluck('image_path')->toArray();

        if (!empty($imagePaths)) {
            Storage::disk('public')->delete($imagePaths);
        }

        $note->delete();

        return redirect()->back()->with('success', 'Đã xóa ghi chú!');
    }

    public function togglePin(Note $note): RedirectResponse
    {
        if ($note->user_id !== Auth::id()) abort(403);
        
        $note->is_pinned = !$note->is_pinned;
        $note->pinned_at = $note->is_pinned ? now() : null;
        $note->save();
        
        return redirect()->back();
    }

    public function lock(Request $request, Note $note): RedirectResponse
    {
        if ($note->user_id !== Auth::id()) abort(403);

        // Validate bắt buộc nhập 2 lần trùng nhau và tối thiểu 4 ký tự
        $request->validate([
            'password' => 'required|string|min:4|confirmed',
        ]);

        // Lưu mật khẩu (Tự động hash nhờ cast 'hashed' ở Model)
        $note->note_password = $request->password;
        $note->is_locked = true; // Chuyển trạng thái sang Đang Khóa
        $note->save();

        return back()->with('success', 'Đã thiết lập mật khẩu thành công!');
    }

    public function unlockTemp(Request $request, Note $note): RedirectResponse
    {
        if ($note->user_id !== Auth::id()) abort(403);

        $request->validate([
            'password' => 'required|string',
        ]);

        // Check xem pass nhập vào có khớp với pass đang lưu của Note không
        if (!Hash::check($request->password, $note->note_password)) {
            return back()->with('error', 'Mật khẩu ghi chú không chính xác!');
        }

        $note->is_locked = false; // Mở khóa trạng thái hiển thị
        $note->save();

        return back()->with('success', 'Đã mở khóa ghi chú.');
    }

    public function removePw(Request $request, Note $note): RedirectResponse
    {
        if ($note->user_id !== Auth::id()) abort(403);

        $request->validate([
            'password' => 'required|string', // Nhập mật khẩu hiện tại để xác thực gỡ bỏ
        ]);

        if (!Hash::check($request->password, $note->note_password)) {
            return back()->with('error', 'Mật khẩu xác nhận không chính xác!');
        }

        // XÓA SẠCH mật khẩu trong database
        $note->note_password = null; 
        $note->is_locked = false;
        $note->save();

        return back()->with('success', 'Đã gỡ bỏ mật khẩu!');
    }
}
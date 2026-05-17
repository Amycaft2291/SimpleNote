<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Note;
use App\Models\NoteImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
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

        $query = Note::where('user_id', $userId)->with(['labels', 'images']);

        if ($request->filled('label')) 
        {
            $query->whereHas('labels', function($q) use ($request) 
            {
                $q->where('labels.id', $request->label);
            });
        }

        if ($request->filled('search')) 
        {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) 
            {
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
        $validated = $request->validate
        ([
            'title'       => 'nullable|string|max:255',
            'content'     => 'nullable|string',
            'labels'   => 'nullable|array',
            'labels.*' => 'integer|exists:labels,id',
            'images'      => 'nullable|array',
            'images.*'    => 'file|image|max:5120',
        ]);

        $note = Note::create
        ([
            'title'   => $validated['title'] ?? '(Không có tiêu đề)',
            'content' => $validated['content'] ?? '',
            'user_id' => Auth::id(),
        ]);

        if (!empty($validated['labels'])) 
        {
            $ids = Label::where('user_id', Auth::id())
                        ->whereIn('id', $validated['labels'])
                        ->pluck('id');
            $note->labels()->sync($ids);
        }

        if ($request->hasFile('images')) 
        {
            foreach ($request->file('images') as $file) 
            {
                $path = $file->store('note_images', 'public');
                NoteImage::create(['note_id' => $note->id, 'image_path' => $path]);
            }
        }
        return redirect()->back()->with('success', 'Đã tạo ghi chú thành công!');
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        if ($note->user_id !== Auth::id()) abort(403);
        $validated = $request->validate
        ([
            'title'   => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'labels'   => 'nullable|array',
            'labels.*' => 'integer|exists:labels,id',
        ]);

        $note->update
        ([
            'title'   => $request->title ?? '(Không có tiêu đề)',
            'content' => $request->content,
        ]);

        $labelIds = [];
        if ($request->has('labels')) 
        {
            $labelIds = Label::where('user_id', Auth::id())
                             ->whereIn('id', $request->input('labels'))
                             ->pluck('id');
        }

        $note->labels()->sync($labelIds);
        if ($request->hasFile('images')) 
        {
            foreach ($request->file('images') as $file) 
            {
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
        if ($image->note->user_id !== auth()->id()) 
        {
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

        if (!empty($imagePaths)) 
        {
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

    public function unlock(Request $request, Note $note): RedirectResponse
    {
        if ($note->user_id !== Auth::id()) abort(403);

        $request->validate([
            'password' => 'required|string',
        ]);

        if (Hash::check($request->password, $note->note_password)) {
            //lưu session
            session()->put("unlocked_notes.{$note->id}", true);
            return redirect()->back();
        }

        return redirect()->back()->withErrors(['unlock_password' => 'Mật khẩu ghi chú không chính xác!']);
    }

    public function lock(Request $request, Note $note): RedirectResponse
    {
        if ($note->user_id !== Auth::id()) abort(403);

        //xác nhận lại mk
        $request->validate([
            'password' => 'required|string|min:4|confirmed',
        ], [
            'password.confirmed' => 'Mật khẩu xác nhận không trùng khớp.',
        ]);

        $note->update([
            'is_locked' => true,
            'note_password' => Hash::make($request->password)//đồng bộ note_password
        ]);

        return redirect()->back()->with('success', 'Đã đặt mật khẩu cho ghi chú thành công!');
    }

    public function reLock(Note $note): RedirectResponse
    {
        if ($note->user_id !== Auth::id()) abort(403);

        //xóa dấu vết đã mở khóa ghi chú này trong session
        session()->forget("unlocked_notes.{$note->id}");

        return redirect()->back()->with('success', 'Đã khóa ghi chú.');
    }

    //gỡ pass
    public function disableLock(Request $request, Note $note): RedirectResponse
    {
        if ($note->user_id !== Auth::id()) abort(403);

        $request->validate([
            'confirm_password' => 'required|string',
        ]);

        //yc pass hiện tại
        if (!Hash::check($request->confirm_password, $note->note_password)) {
            throw ValidationException::withMessages(['confirm_password' => 'Mật khẩu xác nhận không chính xác.']);
        }

        $note->update([
            'is_locked' => false,
            'note_password' => null
        ]);

        //xóa bộ nhớ tạm session
        session()->forget("unlocked_notes.{$note->id}");

        return redirect()->back()->with('success', 'Đã gỡ mật khẩu.');
    }
}
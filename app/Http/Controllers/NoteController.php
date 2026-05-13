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
            'title'       => 'nullable|string|max:255',
            'content'     => 'nullable|string',
            'label_ids'   => 'nullable|array',
            'label_ids.*' => 'integer|exists:labels,id',
            'images'      => 'nullable|array',
            'images.*'    => 'file|image|max:5120',
        ]);

        $note = Note::create([
            'title'   => $validated['title'] ?? '(Không có tiêu đề)',
            'content' => $validated['content'] ?? '',
            'user_id' => Auth::id(),
        ]);

        if (!empty($validated['label_ids'])) {
            $ids = Label::where('user_id', Auth::id())->whereIn('id', $validated['label_ids'])->pluck('id');
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
            'title'   => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'label_ids'   => 'nullable|array',
        ]);

        $note->update([
            'title'   => $validated['title'] ?? $note->title,
            'content' => $validated['content'] ?? $note->content,
        ]);

        if (isset($validated['label_ids'])) {
            $note->labels()->sync($validated['label_ids']);
        }

        return redirect()->back()->with('success', 'Đã cập nhật ghi chú!');
    }

    public function destroy(Note $note): RedirectResponse
    {
        if ($note->user_id !== Auth::id()) abort(403);
        
        foreach ($note->images as $img) {
            Storage::disk('public')->delete($img->image_path);
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

    public function toggleLock(Note $note): RedirectResponse
    {
        if ($note->user_id !== Auth::id()) abort(403);

        $user = Auth::user();

        if (!$user->note_password) {
            return redirect()->route('settings.profile')->with('error', 'Vui lòng thiết lập mật khẩu bảo mật trước!');
        }

        $note->is_locked = !$note->is_locked;
        $note->save();
        
        return redirect()->back()->with('success', $note->is_locked ? 'Đã khóa ghi chú' : 'Đã mở khóa ghi chú');
    }

    public function setNotePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string|min:4|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'note_password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Đã thiết lập mật khẩu thành công!');
    }
}
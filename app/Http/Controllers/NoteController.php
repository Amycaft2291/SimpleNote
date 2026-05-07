<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Note;
use App\Models\NoteImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())
            ->with(['labels', 'images'])
            ->orderByDesc('is_pinned')
            ->orderByDesc('pinned_at')
            ->orderByDesc('updated_at')
            ->get();

        $labels = Label::where('user_id', Auth::id())
            ->withCount('notes')
            ->orderBy('name')
            ->get();

        return view('dashboard', compact('notes', 'labels'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'nullable|string',
            'label_ids'   => 'nullable|array',
            'label_ids.*' => 'integer|exists:labels,id',
            'images'      => 'nullable|array',
            'images.*'    => 'file|image|max:5120',
        ]);

        $note = Note::create([
            'title'   => $validated['title'],
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
                \App\Models\NoteImage::create(['note_id' => $note->id, 'image_path' => $path]);
            }
        }

        return response()->json(['success' => true, 'note' => $note], 201);
    }

    public function update(Request $request, Note $note): JsonResponse
    {
        if ($note->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title'   => 'sometimes|required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $note->update([
            'title'   => $validated['title'] ?? $note->title,
            'content' => $validated['content'] ?? $note->content,
        ]);

        return response()->json(['success' => true, 'note' => $note]);
    }

    public function destroy(Note $note): JsonResponse
    {
        if ($note->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        foreach ($note->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        $note->delete();
        
        return response()->json(['success' => true]);
    }

    public function togglePin(Note $note): JsonResponse
    {
        if ($note->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $note->is_pinned = !$note->is_pinned;
        $note->pinned_at = $note->is_pinned ? now() : null;
        $note->save();
        
        return response()->json(['success' => true]);
    }
}
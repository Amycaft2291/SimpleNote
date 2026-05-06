<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{
    /**
     * Hiển thị danh sách ghi chú của user hiện tại.
     * Sắp xếp: is_pinned DESC → pinned_at DESC → created_at DESC
     */
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())
            ->orderByDesc('is_pinned')
            ->orderByDesc('pinned_at')
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard', compact('notes'));
    }

    /**
     * Lưu ghi chú mới (AJAX).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $note = Note::create([
            'title'   => $validated['title'],
            'content' => $validated['content'] ?? '',
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'note'    => $note,
        ], 201);
    }

    /**
     * Cập nhật ghi chú (AJAX) — bảo vệ bằng user_id.
     */
    public function update(Request $request, Note $note): JsonResponse
    {
        // Đảm bảo chỉ chủ sở hữu mới được sửa
        if ($note->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title'   => 'sometimes|required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $note->update($validated);

        return response()->json(['success' => true, 'note' => $note]);
    }

    /**
     * Xóa ghi chú (AJAX) — bảo vệ bằng user_id.
     */
    public function destroy(Note $note): JsonResponse
    {
        if ($note->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $note->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Toggle ghim / bỏ ghim ghi chú (AJAX).
     */
    public function togglePin(Note $note): JsonResponse
    {
        if ($note->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $note->is_pinned = !$note->is_pinned;
        $note->pinned_at = $note->is_pinned ? now() : null;
        $note->save();

        return response()->json(['success' => true, 'is_pinned' => $note->is_pinned]);
    }
}

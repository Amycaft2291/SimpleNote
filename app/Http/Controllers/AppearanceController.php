<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppearanceController extends Controller
{
    public function edit()
    {
        return view('settings.appearance', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'theme'      => 'required|in:light,dark',
            'font_size'  => 'required|integer|min:12|max:24',
            'note_color' => 'nullable|string|max:20',
        ]);

        $request->user()->update($validated);

        // Lưu theme vào session để apply ngay lập tức không cần reload
        session(['theme' => $validated['theme']]);

        return back()->with('status', 'Đã lưu cấu hình giao diện!');
    }
}
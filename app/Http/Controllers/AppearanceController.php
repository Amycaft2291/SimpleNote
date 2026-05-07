<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppearanceController extends Controller
{
    public function edit()
    {
        return view('settings.appearance', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $allowedColors = ['#ffffff', '#fef9c3', '#dcfce7', '#e0f2fe', '#fee2e2', '#f3f4f6', '#1e293b'];

        $validated = $request->validate([
            'theme'      => 'required|in:light,dark,system',
            'font_size'  => 'required|integer|min:12|max:24',
            'note_color' => 'nullable|in:' . implode(',', $allowedColors),
        ]);

        $request->user()->update($validated);

        return back()->with('status', 'Đã lưu cấu hình giao diện!');
    }
}
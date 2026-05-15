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
        'theme' => 'required|in:light,dark',
        'font_size' => 'required|integer|min:12|max:24',
    ]);

    $user = $request->user();

    $user->theme = $validated['theme'];
    $user->font_size = $validated['font_size'];

    $user->save();

    session([
        'theme' => $user->theme
    ]);

    return back()->with('status', 'Đã lưu giao diện!');
}
}
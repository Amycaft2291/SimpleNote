<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SecurityController extends Controller
{
    public function index()
    {
        return view('settings.security', [
            'user' => auth()->user(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ], [
            'password.min'       => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Mật khẩu hiện tại không đúng!',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'Đổi mật khẩu thành công!');
    }
}
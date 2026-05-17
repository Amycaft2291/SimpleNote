<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('settings.profile', ['user' => $request->user(),]);
    }

   public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->display_name = $request->display_name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) 
        {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        if ($user->isDirty('email')) 
        {
            $user->email_verified_at = null;
            $user->save();
            $user->sendEmailVerificationNotification();
            return Redirect::route('settings.profile')->with('status', 'Cập nhật thành công! Vui lòng xác minh email mới.');
        }

        $user->save();
        return Redirect::route('settings.profile')->with('status', 'Cập nhật hồ sơ thành công!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', ['password' => ['required', 'current_password'],]);
        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
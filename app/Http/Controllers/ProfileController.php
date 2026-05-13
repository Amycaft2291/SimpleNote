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
        return view('settings.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        // Nếu đổi email → reset trạng thái xác minh, gửi lại email verify
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
            $request->user()->save();
            $request->user()->sendEmailVerificationNotification();

            return Redirect::route('settings.profile')
                ->with('status', 'Cập nhật thành công! Vui lòng xác minh email mới của bạn.');
        }

        $request->user()->save();

        return Redirect::route('settings.profile')
            ->with('status', 'Cập nhật hồ sơ thành công!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'display_name' => $request->display_name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Tự động băm Bcrypt [cite: 7, 188]
            'activation_token' => Str::random(60), // Tạo token để gửi qua email [cite: 180]
        ]);

        event(new Registered($user));

        Auth::login($user); // Đăng nhập tự động sau khi đăng ký thành công [cite: 8, 218]

        // Đoạn này để gửi email
        // \Illuminate\Support\Facades\Mail::send('emails.activation', ['user' => $user], function ($message) use ($user) {
        //     $message->to($user->email);
        //     $message->subject('Kích hoạt tài khoản SimpleNote');
        // });

        return redirect(route('dashboard', absolute: false));
    }
}

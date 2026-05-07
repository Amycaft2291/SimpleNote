<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SimpleNote — Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/auth/style_LoginAndRegister.css') }}">

</head>
<body>

<div class="card">
    <!-- Left decorative panel -->
    <div class="panel-left">
        <div class="brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 5.58 2 10c0 2.56 1.36 4.84 3.5 6.37V20l3.5-2.1c.96.22 1.96.35 3 .35 5.52 0 10-3.58 10-8S17.52 2 12 2z"/>
                </svg>
            </div>
            <span class="brand-name">SimpleNote</span>
        </div>

        <p class="tagline">Capture every thought.<br>Access anywhere, anytime.<br>Stay organized effortlessly.</p>
        <a href="#" class="btn-learn">Learn More</a>
    </div>

    <!-- Right form panel -->
    <div class="panel-right">
        <button class="close-btn" aria-label="Close">✕</button>

        <h1>Welcome Back!</h1>
        <p class="subtitle">Sign in to continue</p>

        <div class="dots">
            <span class="dot active"></span>
            <span class="dot"></span>
        </div>

        @if (session('status'))
            <div style="width:100%;padding:10px 14px;background:#edf9f0;border:1px solid #6fcf97;border-radius:8px;font-size:13px;color:#219653;margin-bottom:16px;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                >
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                    autocomplete="current-password"
                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                >
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <label class="remember">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                @endif
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        @if (Route::has('register'))
            <p class="register-link">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
        @endif
    </div>
</div>

</body>
</html>
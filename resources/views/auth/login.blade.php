<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Authentication</title>

    <!-- BOXICONS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/auth/style_LoginAndRegister.css') }}">
</head>

<body>

<div class="container">

    <!-- LOGIN -->
    <div class="form-box login">

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <h1>Login</h1>

            <!-- SUCCESS -->
            @if(session('status'))
                <div class="success-message">
                    {{ session('status') }}
                </div>
            @endif

            <!-- EMAIL -->
            <div class="input-box">
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value="{{ old('email') }}"
                    required
                >
                <i class='bx bxs-envelope'></i>
            </div>

            @error('email')
                <span class="error-text">
                    {{ $message }}
                </span>
            @enderror

            <!-- PASSWORD -->
            <div class="input-box">
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                >
                <i class='bx bxs-lock-alt'></i>
            </div>

            @error('password')
                <span class="error-text">
                    {{ $message }}
                </span>
            @enderror

            <!-- FORGOT -->
            <div class="form-row">

                <label class="remember">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        Forgot password?
                    </a>
                @endif

            </div>

            <button type="submit" class="btn">
                Login
            </button>

        </form>
    </div>

    <!-- REGISTER -->
    <div class="form-box register">

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <h1>Registration</h1>

            <!-- SUCCESS -->
            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            <!-- DISPLAY NAME -->
            <div class="input-box">
                <input
                    type="text"
                    name="display_name"
                    placeholder="Username"
                    value="{{ old('display_name') }}"   
                    required
                >
                <i class='bx bxs-user'></i>
            </div>

            @error('display_name')
            <span class="error-text">
                {{ $message }}
            </span>
            @enderror

            <!-- EMAIL -->
            <div class="input-box">
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value="{{ old('email') }}"
                    required
                >
                <i class='bx bxs-envelope'></i>
            </div>

            @error('email')
            <span class="error-text">
                {{ $message }}
            </span>
            @enderror

            <!-- PASSWORD -->
            <div class="input-box">
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                >
                <i class='bx bxs-lock-alt'></i>
            </div>

            @error('password')
                <span class="error-text">
                    {{ $message }}
                </span>
            @enderror

            <!-- CONFIRM PASSWORD -->
            <div class="input-box">
                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm Password"
                    required
                >
                <i class='bx bxs-lock-alt'></i>
            </div>

            <button type="submit" class="btn">
                Register
            </button>

        </form>
    </div>

    <!-- TOGGLE -->
    <div class="toggle-box">

        <!-- LEFT -->
        <div class="toggle-panel toggle-left">
            <h1>Hello, Welcome!</h1>
            <p>Don't have an account?</p>

            <button type="button" class="btn register-btn">
                Register
            </button>
        </div>

        <!-- RIGHT -->
        <div class="toggle-panel toggle-right">
            <h1>Welcome Back!</h1>
            <p>Already have an account?</p>

            <button type="button" class="btn login-btn">
                Login
            </button>
        </div>

    </div>

</div>

<script>
    const container = document.querySelector('.container');
    const registerBtn = document.querySelector('.register-btn');
    const loginBtn = document.querySelector('.login-btn');

    registerBtn.addEventListener('click', () => {
        container.classList.add('active');
    });

    loginBtn.addEventListener('click', () => {
        container.classList.remove('active');
    });
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

    <link rel="stylesheet" href="{{ asset('css/auth/style_LoginAndRegister.css') }}">

    <style>
        /* ===== RESET PASSWORD LAYOUT FIX ===== */

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(90deg,#e2e2e2,#c9d6ff);
        }

        .container {
            width: 420px;
        }

        .form-box.login {
            width: 100%;
            padding: 40px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 0 30px rgba(0,0,0,.15);
            text-align: center;
        }

        .form-box h1 {
            margin-bottom: 25px;
            font-size: 28px;
            color: #333;
        }

        .input-box {
            margin: 18px 0;
        }

        .input-box input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: none;
            background: #eee;
            outline: none;
            font-size: 15px;
        }

        .btn {
            width: 100%;
            height: 45px;
            background: #7494ec;
            border-radius: 8px;
            border: none;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            background: #5f7fe0;
        }
    </style>

</head>

<body>

<div class="container">

    <div class="form-box login">

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <h1>Reset Password</h1>

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- EMAIL -->
            <div class="input-box">
                <input type="email"
                       name="email"
                       placeholder="Email"
                       value="{{ old('email', $request->email) }}"
                       required>
            </div>

            <!-- NEW PASSWORD -->
            <div class="input-box">
                <input type="password"
                       name="password"
                       placeholder="New Password"
                       required>
            </div>

            <!-- CONFIRM -->
            <div class="input-box">
                <input type="password"
                       name="password_confirmation"
                       placeholder="Confirm Password"
                       required>
            </div>

            <button type="submit" class="btn">
                Reset Password
            </button>

        </form>

    </div>

</div>

</body>
</html>
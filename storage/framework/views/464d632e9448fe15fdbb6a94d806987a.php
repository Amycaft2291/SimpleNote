<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>Quên mật khẩu</title>

    <!-- BOXICONS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body{
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(90deg,#e2e2e2,#c9d6ff);
            overflow: hidden;
        }

        .forgot-container{
            width: 850px;
            height: 550px;
            background: #fff;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(0,0,0,.2);
            display: flex;
        }

        /* LEFT PANEL */

        .left-panel{
            width: 50%;
            background: #7494ec;
            color: #fff;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .left-panel h1{
            font-size: 42px;
            margin-bottom: 20px;
        }

        .left-panel p{
            font-size: 16px;
            line-height: 1.7;
            opacity: .9;
            margin-bottom: 30px;
        }

        .left-panel .icon-box{
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255,255,255,.15);
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 25px;
        }

        .left-panel .icon-box i{
            font-size: 42px;
        }

        /* RIGHT PANEL */

        .right-panel{
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background: #fff;
        }

        .form-box{
            width: 100%;
        }

        .form-box h2{
            font-size: 34px;
            color: #333;
            margin-bottom: 10px;
        }

        .subtitle{
            color: #777;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .input-box{
            position: relative;
            margin-bottom: 25px;
        }

        .input-box input{
            width: 100%;
            height: 52px;
            background: #eee;
            border: none;
            outline: none;
            border-radius: 10px;
            padding: 0 50px 0 20px;
            font-size: 15px;
            color: #333;
        }

        .input-box input::placeholder{
            color: #888;
        }

        .input-box i{
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #888;
        }

        .btn-reset{
            width: 100%;
            height: 50px;
            background: #7494ec;
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: .3s;
        }

        .btn-reset:hover{
            background: #5f7fe0;
        }

        .alert-success{
            background: #eafaf1;
            border: 1px solid #2ecc71;
            color: #27ae60;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .text-danger{
            color: red;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .back-login{
            margin-top: 20px;
            text-align: center;
        }

        .back-login a{
            text-decoration: none;
            color: #7494ec;
            font-size: 14px;
            font-weight: 500;
            transition: .3s;
        }

        .back-login a:hover{
            color: #5f7fe0;
        }

        @media screen and (max-width:768px){

            .forgot-container{
                width: 95%;
                height: auto;
                flex-direction: column;
            }

            .left-panel,
            .right-panel{
                width: 100%;
            }

            .left-panel{
                padding: 40px 30px;
                text-align: center;
                align-items: center;
            }

            .right-panel{
                padding: 40px 25px;
            }

            .left-panel h1{
                font-size: 32px;
            }
        }
    </style>
</head>

<body>

<div class="forgot-container">

    <!-- LEFT -->
    <div class="left-panel">

        <div class="icon-box">
            <i class='bx bx-lock-open-alt'></i>
        </div>

        <h1>Forgot Password?</h1>

        <p>
            Don’t worry. Enter your registered email address and
            we’ll send you a password reset link immediately.
        </p>

    </div>

    <!-- RIGHT -->
    <div class="right-panel">

        <div class="form-box">

            <h2>Reset Password</h2>

            <p class="subtitle">
                Enter your email to receive reset instructions
            </p>

            <?php if(session('status')): ?>
                <div class="alert-success">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.email')); ?>">
                <?php echo csrf_field(); ?>

                <div class="input-box">
                    <input
                        type="email"
                        name="email"
                        placeholder="Email Address"
                        required
                    >

                    <i class='bx bxs-envelope'></i>
                </div>

                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small class="text-danger">
                        <?php echo e($message); ?>

                    </small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <button type="submit" class="btn-reset">
                    Send Reset Link
                </button>
            </form>

            <div class="back-login">
                <a href="<?php echo e(route('login')); ?>">
                    ← Back to Login
                </a>
            </div>

        </div>

    </div>

</div>

</body>
</html><?php /**PATH C:\CK Web\Composer\SimpleNote\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>
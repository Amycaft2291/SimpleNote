<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Authentication</title>

    <!-- BOXICONS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/auth/style_LoginAndRegister.css')); ?>">
</head>

<body>

<div class="container">

    <!-- LOGIN -->
    <div class="form-box login">

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <h1>Login</h1>

            <!-- SUCCESS -->
            <?php if(session('status')): ?>
                <div class="success-message">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <!-- EMAIL -->
            <div class="input-box">
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value="<?php echo e(old('email')); ?>"
                    required
                >
                <i class='bx bxs-envelope'></i>
            </div>

            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="error-text">
                    <?php echo e($message); ?>

                </span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

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

            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="error-text">
                    <?php echo e($message); ?>

                </span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <!-- FORGOT -->
            <div class="form-row">

                <label class="remember">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>

                <?php if(Route::has('password.request')): ?>
                    <a href="<?php echo e(route('password.request')); ?>" class="forgot-link">
                        Forgot password?
                    </a>
                <?php endif; ?>

            </div>

            <button type="submit" class="btn">
                Login
            </button>

        </form>
    </div>

    <!-- REGISTER -->
    <div class="form-box register">

        <form method="POST" action="<?php echo e(route('register')); ?>">
            <?php echo csrf_field(); ?>

            <h1>Registration</h1>

            <!-- SUCCESS -->
            <?php if(session('success')): ?>
                <div class="success-message">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <!-- DISPLAY NAME -->
            <div class="input-box">
                <input
                    type="text"
                    name="display_name"
                    placeholder="Username"
                    value="<?php echo e(old('display_name')); ?>"   
                    required
                >
                <i class='bx bxs-user'></i>
            </div>

            <?php $__errorArgs = ['display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="error-text">
                <?php echo e($message); ?>

            </span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <!-- EMAIL -->
            <div class="input-box">
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value="<?php echo e(old('email')); ?>"
                    required
                >
                <i class='bx bxs-envelope'></i>
            </div>

            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="error-text">
                <?php echo e($message); ?>

            </span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

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

            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="error-text">
                    <?php echo e($message); ?>

                </span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

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
</html><?php /**PATH C:\CK Web\Composer\SimpleNote\resources\views/auth/login.blade.php ENDPATH**/ ?>
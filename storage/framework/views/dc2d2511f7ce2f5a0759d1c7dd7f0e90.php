<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SimpleNote — Create Account</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo e(asset('css/auth/style_LoginAndRegister.css')); ?>">
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

        <div class="perks">
            <div class="perk"><span class="perk-dot"></span>Free forever, no credit card needed</div>
            <div class="perk"><span class="perk-dot"></span>Sync notes across all your devices</div>
            <div class="perk"><span class="perk-dot"></span>Light & dark themes, custom fonts</div>
            <div class="perk"><span class="perk-dot"></span>Password reset via email or OTP</div>
        </div>
    </div>

    <!-- Right form panel -->
    <div class="panel-right">
        <button class="close-btn" aria-label="Close">✕</button>

        <h1>Welcome!</h1>
        <p class="subtitle">Register now, it's free!</p>

        <div class="dots">
            <span class="dot"></span>
            <span class="dot active"></span>
        </div>

        <form method="POST" action="<?php echo e(route('register')); ?>">
            <?php echo csrf_field(); ?>

            
            <div class="form-group">
                <input
                    type="text"
                    name="display_name"
                    placeholder="Full Name"
                    value="<?php echo e(old('display_name')); ?>"
                    required
                    autofocus
                    autocomplete="name"
                    class="<?php echo e($errors->has('display_name') ? 'is-invalid' : ''); ?>"
                >
                <?php $__errorArgs = ['display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="form-group">
                <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    value="<?php echo e(old('email')); ?>"
                    required
                    autocomplete="username"
                    class="<?php echo e($errors->has('email') ? 'is-invalid' : ''); ?>"
                >
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="form-group">
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                    autocomplete="new-password"
                    class="<?php echo e($errors->has('password') ? 'is-invalid' : ''); ?>"
                >
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <span class="password-hint">Minimum 8 characters</span>
            </div>

            
            <div class="form-group">
                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm Password"
                    required
                    autocomplete="new-password"
                    class="<?php echo e($errors->has('password_confirmation') ? 'is-invalid' : ''); ?>"
                >
                <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="btn-register">Get Started</button>
        </form>

        <p class="login-link">Already have an account? <a href="<?php echo e(route('login')); ?>">Login</a></p>
    </div>
</div>

</body>
</html><?php /**PATH C:\CK Web\Composer\SimpleNote\resources\views/auth/register.blade.php ENDPATH**/ ?>
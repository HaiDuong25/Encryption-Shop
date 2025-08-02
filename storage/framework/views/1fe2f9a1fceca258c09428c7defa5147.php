<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In / Sign Up</title>
    <link rel="stylesheet" href="<?php echo e(asset('34/sign-in-sign-up-form.css')); ?>">
</head>

<body>
    <div class="container" id="container">
        <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div><?php echo e($error); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        
        <div class="form-container sign-up-container">
            <form method="POST" action="<?php echo e(route('register')); ?>">
                <?php echo csrf_field(); ?>
                <h1>Create Account</h1>
                <div class="social-container">
                    <a href="#" class="social"><img src="<?php echo e(asset('34/images/facebook.png')); ?>" alt=""></a>
                    <a href="#" class="social"><img src="<?php echo e(asset('34/images/google.png')); ?>" alt=""></a>
                    <a href="#" class="social"><img src="<?php echo e(asset('34/images/instagram.png')); ?>" alt=""></a>
                </div>
                <span>or use your email for registration</span>
                <input type="text" name="name" placeholder="Name" value="<?php echo e(old('name')); ?>" required />
                <input type="email" name="email" placeholder="Email" value="<?php echo e(old('email')); ?>" required />
                <input type="password" name="password" placeholder="Password" required />
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small style="color:red"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small style="color:red"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <button type="submit">Sign Up</button>
            </form>
        </div>

        
        <div class="form-container sign-in-container">
            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                <h1>Sign in</h1>
                <div class="social-container">
                    <a href="#" class="social"><img src="<?php echo e(asset('34/images/facebook.png')); ?>" alt=""></a>
                    <a href="#" class="social"><img src="<?php echo e(asset('34/images/google.png')); ?>" alt=""></a>
                    <a href="#" class="social"><img src="<?php echo e(asset('34/images/instagram.png')); ?>" alt=""></a>
                </div>
                <span>or use your account</span>
                <input type="email" name="email" placeholder="Email" value="<?php echo e(old('email')); ?>" required />
                <input type="password" name="password" placeholder="Password" required />
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small style="color:red"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <a href="#">Forgot your password?</a>
                <button type="submit">Sign In</button>
            </form>
        </div>

        
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('34/sign-in-sign-up-form.js')); ?>"></script>
</body>

</html>
<?php /**PATH C:\laragon\www\DATN\Encryption-Shop\resources\views/auth/auth.blade.php ENDPATH**/ ?>
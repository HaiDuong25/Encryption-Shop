<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In / Sign Up</title>
    <link rel="stylesheet" href="{{ asset('34/sign-in-sign-up-form.css') }}">
</head>

<body>
    <div class="container" id="container">
        @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        {{-- Sign Up --}}
        <div class="form-container sign-up-container">
            <form method="POST" action="{{ route('auth.register') }}">
                @csrf
                <h1>Create Account</h1>
                <div class="social-container">
                    <a href="#" class="social"><img src="{{ asset('34/images/facebook.png') }}" alt=""></a>
                    <a href="#" class="social"><img src="{{ asset('34/images/google.png') }}" alt=""></a>
                    <a href="#" class="social"><img src="{{ asset('34/images/instagram.png') }}" alt=""></a>
                </div>
                <span>or use your email for registration</span>
                <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required />
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
                <input type="password" name="password" placeholder="Password" required />
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
                @error('email')<small style="color:red">{{ $message }}</small>@enderror
                @error('password')<small style="color:red">{{ $message }}</small>@enderror
                <button type="submit">Sign Up</button>
            </form>
        </div>

        {{-- Sign In --}}
        <div class="form-container sign-in-container">
            <form method="POST" action="{{ route('auth.login') }}">
                @csrf
                <h1>Sign in</h1>
                <div class="social-container">
                    <a href="#" class="social"><img src="{{ asset('34/images/facebook.png') }}" alt=""></a>
                    <a href="#" class="social"><img src="{{ asset('34/images/google.png') }}" alt=""></a>
                    <a href="#" class="social"><img src="{{ asset('34/images/instagram.png') }}" alt=""></a>
                </div>
                <span>or use your account</span>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
                <input type="password" name="password" placeholder="Password" required />
                @error('email')<small style="color:red">{{ $message }}</small>@enderror
                <a href="#">Forgot your password?</a>
                <button type="submit">Sign In</button>
            </form>
        </div>

        {{-- Overlay --}}
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

    <script src="{{ asset('34/sign-in-sign-up-form.js') }}"></script>
</body>

</html>

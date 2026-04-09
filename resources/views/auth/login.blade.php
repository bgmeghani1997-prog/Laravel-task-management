<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="bg-light">
        <div class="d-flex justify-content-center align-items-center py-4" style="min-height: 100vh;">
            <div style="max-width: 400px; width: 100%;">
                <div class="text-center mb-4">
                    <h2>{{ config('app.name', 'Laravel') }}</h2>
                </div>
                <div class="card">
                    <div class="card-body">
                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success mb-3">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login', absolute: false) }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-3 form-check">
                                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request', absolute: false) }}" class="link-primary text-decoration-none small">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif

                                <button type="submit" class="btn btn-primary">{{ __('Log in') }}</button>
                            </div>

                            <div class="mt-3 text-center">
                                <p class="mb-0 small">{{ __("Don't have an account?") }} <a href="{{ route('register', absolute: false) }}" class="link-primary text-decoration-none">{{ __('Register') }}</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

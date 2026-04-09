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
                        <div class="mb-3 alert alert-info">
                            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                        </div>

                        @if (session('status') == 'verification-link-sent')
                            <div class="mb-3 alert alert-success">
                                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                            </div>
                        @endif

                        <div class="mt-3 d-flex justify-content-between align-items-center gap-2">
                            <form method="POST" action="{{ route('verification.send', absolute: false) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">{{ __('Resend') }}</button>
                            </form>

                            <form method="POST" action="{{ route('logout', absolute: false) }}">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm">{{ __('Log Out') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

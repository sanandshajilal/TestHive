<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>TestHive - Admin Login</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>

    <style>
    body {
        background: linear-gradient(135deg, #e0e7ff, rgb(252, 244, 215));
        font-family: 'Figtree', sans-serif;
    }

    .auth-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 1rem;
    }

    .auth-card {
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(10px);
        border-radius: 1.25rem;
        padding: 2.5rem 2rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }

    .auth-card h2 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1e293b;
    }

    .auth-card p {
        color: #475569;
        font-size: 0.95rem;
    }

    input[type="email"],
    input[type="password"] {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
        padding: 0.75rem;
        width: 90%;
        transition: border 0.2s, box-shadow 0.2s;
        margin-top: 0.4rem;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }

    .login-btn {
        background-color: #6366f1;
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: background-color 0.3s;
    }

    .login-btn:hover {
        background-color: #4f46e5;
    }

    .forgot-link {
        font-size: 0.875rem;
        color: #64748b;
        text-decoration: underline;
        margin-right: auto;
    }

    .forgot-link:hover {
        color: #334155;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        color: #475569;
    }

    .actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 0.5rem;
    }
</style>

</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="text-center" style="margin-bottom: 1.25rem;">
                <h2 style="margin-bottom: 0.25rem;">Hi Maalasri,</h2>
                <p>welcome back to <strong>TestHive</strong> admin portal</p>
            </div>


            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

                <form method="POST" action="{{ url('admin/login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="remember-me">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="remember_me">Remember me</label>
                </div>

                <div class="actions">
                    <a class="forgot-link" href="#">Forgot your password?</a>
                    <button type="submit" class="login-btn">Log in</button>
                </div>
            </form>

        </div>
    </div>
</body>
</html>

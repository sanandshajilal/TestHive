
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>ACCAPrep with Malasri - Admin Login</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

<style>
    body {
        background-color: #f7f8fa;
        background-image: radial-gradient(#e9ecef 1px, transparent 1px);
        background-size: 24px 24px;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        height: 100%;
        margin: 0;
        overflow-x: hidden;
    }

    .auth-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 0;
    }

    .auth-card {
        background: #ffffff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .05);
        width: 100%;
        max-width: 560px;
    }

    .top-bar {
        height: 6px;
        background-color: #832b00;
    }

    .header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.2rem 1.5rem 0;
    }

    .header-left {
        font-size: 1.2rem;
        font-weight: bold;
        color: #343a40;
        letter-spacing: .5px;
    }

    .header-right {
        display: flex;
        align-items: center;
    }

    .divider {
        border-top: 1px solid #e5e7eb;
        margin: 10px 1.5rem 1rem;
    }

    .title-highlight {
        background-color: #f7e3d8;
        color: #9a5631;
        font-weight: 600;
        text-align: center;
        padding: .75rem 1rem;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: .9rem;
        margin: 1.2rem 1.5rem 1.5rem;
    }

    .form-section {
        padding: 0 1.5rem 1.5rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
        width: 100%;
    }

    .form-label {
        display: block;
        font-weight: 500;
        color: #333;
        margin-bottom: .4rem;
    }

    .form-group input[type="email"],
    .form-group input[type="password"] {
        width: 100%;
        box-sizing: border-box;
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: .5rem;
        padding: .75rem;
        transition: .2s ease;
    }

    .form-group input[type="email"]:focus,
    .form-group input[type="password"]:focus {
        outline: none;
        border-color: #b46e4c;
        box-shadow: 0 0 0 3px rgba(180, 110, 76, .15);
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: 1rem;
        color: #475569;
        font-size: .9rem;
    }

    .forgot-link {
        color: #6b7280;
        text-decoration: none;
        font-size: .875rem;
        transition: .2s ease;
    }

    .forgot-link:hover {
        color: #832b00;
    }

    .actions {
        margin-top: 1rem;
    }

    .admin-login-btn {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        background: #b46e4c;
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        transition: all .2s ease;
    }

    .admin-login-btn:hover {
        background: #832b00;
    }

    .admin-login-btn:focus,
    .admin-login-btn:active {
        background: #832b00;
        box-shadow: none;
    }

    .footer-text {
        text-align: center;
        color: #6b7280;
        font-size: .85rem;
        margin-top: 1.25rem;
        line-height: 1.6;
    }

    .brand-logo {
        line-height: 1;
    }

    .error-box {
        margin-bottom: 1rem;
    }

    .text-danger.small {
        margin-top: .35rem;
    }

    @media (max-width: 576px) {

        .auth-wrapper {
            padding: 1rem;
        }

        .auth-card {
            border-radius: 16px;
        }

        .header-row {
            padding: 1rem 1.25rem 0;
        }

        .header-left {
            font-size: 1rem;
        }

        .title-highlight {
            margin: 1rem 1.25rem 1.25rem;
            font-size: .85rem;
        }

        .form-section {
            padding: 0 1.25rem 1.25rem;
        }
    }
</style>
</head>

<body>

<div class="auth-wrapper">

    <div class="auth-card">

        <div class="top-bar"></div>

        <div class="header-row">
            <div class="header-left">
                ADMIN PORTAL
            </div>

           <div class="header-right">
                <img src="{{ asset('images/Logo.png') }}"
                    alt="ACCAPrep by Malasri"
                    height="55">
            </div>
        </div>

        <div class="divider"></div>

        <div class="title-highlight">
            Secure Admin Access
        </div>

        <div class="form-section">


            @if (session('status'))
                <div class="alert alert-success error-box">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ url('admin/login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">
                        Email Address
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                    >

                    @error('email')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    >

                    @error('password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="remember-me">
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                    >

                    <label for="remember_me">
                        Remember me
                    </label>
                </div>

                <div class="mb-3">
                    <a class="forgot-link" href="#">
                        Forgot your password?
                    </a>
                </div>

                <div class="actions">
                    <button type="submit" class="admin-login-btn">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Sign In
                    </button>
                </div>

            </form>

            <div class="footer-text">
                <div>© 2025 ACCAPrep with Malasri</div>
            </div>

        </div>

    </div>

</div>

</body>
</html>


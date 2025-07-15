<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>TestHive</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: linear-gradient(135deg, #e0e7ff,rgb(252, 244, 215));
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
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 1.25rem;
            padding: 2.5rem;
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
            width: 100%;
            transition: border 0.2s, box-shadow 0.2s;
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
            padding: 0.75rem 1.5rem;
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
        }

        .forgot-link:hover {
            color: #334155;
        }
    </style>
</head>
<body>

    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="mb-6 text-center">
                <h2>Hi Maalasri,</h2>
                <p>welcome back to <strong>TestHive</strong> admin portal</p>
            </div>

            {{ $slot }}
        </div>
    </div>

</body>
</html>

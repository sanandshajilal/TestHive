<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ACCAPrep with Malasri</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    {{-- Custom Styles --}}
   <style>
    body {
        background-color: #f7f8fa;
        font-family: 'Segoe UI', sans-serif;
    }

    .navbar-custom {
        background-color: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .student-logo {
        height: 50px;
        width: auto;
        display: block;
    }

    .content-wrapper {
        padding-top: 80px;
    }

    .exit-button {
        border: 1px solid #e5d2c8;
        background-color: #ffffff;
        color: #832b00;
        font-size: 0.9rem;
        padding: 6px 14px;
        border-radius: 20px;
        transition: all 0.2s ease-in-out;
    }

    .exit-button:hover {
        background-color: #f7e3d8;
        border-color: #b46e4c;
        color: #832b00;
    }

    .exit-button:focus,
    .exit-button:active {
        background-color: #f7e3d8;
        border-color: #b46e4c;
        color: #832b00;
        box-shadow: none;
    }

    

    @media (max-width: 576px) {

            .exit-text {
                display: none;
            }

            .exit-button {
                width: 38px;
                height: 38px;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
            }

            .student-logo {
                height: 40px;
            }
        }

</style>

    @stack('styles')
       @yield('styles')
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand text-decoration-none" href="#">
                <img src="{{ asset('images/Logo.png') }}"
                    alt="ACCAPrep with Malasri"
                    class="student-logo">
            </a>


            <div class="d-flex justify-content-end">
                {{-- Exit Button --}}
                <form action="{{ route('student.logout') }}" method="POST" class="d-flex">
                    @csrf
                    <button type="submit" class="exit-button">
                        <i class="bi bi-door-closed"></i>
                        <span class="exit-text ms-1">Exit</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="container content-wrapper">
        @yield('content')
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

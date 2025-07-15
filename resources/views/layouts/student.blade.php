<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Access | ACCA Mock Tests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

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

        .navbar-brand {
            font-weight: 600;
            color: #4e73df;
            font-size: 1.25rem;
        }

        .navbar-brand:hover {
            color: #3756c0;
        }

        .content-wrapper {
            padding-top: 80px;
        }

        .exit-button {
            border: 1px solid #dee2e6;
            background-color: #ffffff;
            color: #555;
            font-size: 0.9rem;
            padding: 6px 14px;
            border-radius: 20px;
            transition: all 0.2s ease-in-out;
        }

        .exit-button:hover {
            background-color: #f8d7da;
            color: #842029;
            border-color: #f5c2c7;
        }
        .brand-logo {
        line-height: 1.1;
            }

            .brand-subtext {
                font-size: 0.6rem;
                color: rgba(169, 169, 169, 0.86);
                margin-left: 1.7rem;
            }

    </style>

    @stack('styles')
       @yield('styles')
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand brand-logo d-flex flex-column align-items-start text-decoration-none" href="#">
                <div class="d-flex align-items-center header-right fw-bold">
                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i>
                    TestHive
                </div>
                <div class="brand-subtext">by <trong>MALASRI</strong></div>
            </a>


            <div class="collapse navbar-collapse justify-content-end" id="studentNavbar">
                {{-- Exit Button --}}
                <form action="{{ route('student.logout') }}" method="POST" class="d-flex">
                    @csrf
                    <button type="submit" class="exit-button">
                        <i class="bi bi-door-closed me-1"></i>Exit
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

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>

    .navbar {
        position: sticky;
        top: 0;
        z-index: 1030;

        background: rgba(255, 255, 255, 0.50);
        backdrop-filter: blur(10px);

         border-bottom: 1px solid rgba(0,0,0,.06); 
        box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
    }

    /* Brand */

    .navbar-brand {
        padding: .35rem 0;
    }

    .navbar-brand img {
        height: 42px;
        width: auto;
    }

    /* Navigation */

    .navbar .nav-link {
        color: #4b5563 !important;
        font-weight: 500;
        transition: all .2s ease;
    }

    .navbar .nav-link:hover,
    .navbar .nav-link:focus {
        color: #832b00 !important;
    }

    .navbar .nav-link.active {
        color: #b46e4c !important;
        font-weight: 600;
    }

    /* Dropdown */

    .navbar .dropdown-menu {
        border: none;
        border-radius: 12px;
        padding: .5rem;
        margin-top: .6rem;
        box-shadow: 0 10px 24px rgba(0, 0, 0, .08);
    }

    .navbar .dropdown-item {
        border-radius: 8px;
        color: #4b5563;
        padding: .55rem .9rem;
        transition: all .2s ease;
    }

    .navbar .dropdown-item:hover,
    .navbar .dropdown-item:focus {
        background: #f7e3d8;
        color: #832b00;
    }

    /* Logout */

    .navbar .btn-logout {
        border: none;
        background: transparent;
        color: #6b7280 !important;
        padding: .5rem 0;
        transition: all .2s ease;
        text-decoration: none;
    }

    .navbar .btn-logout:hover {
        color: #832b00 !important;
    }

    /* Icons */

    .navbar i {
        font-size: .95rem;
    }

    /* Mobile */

    .navbar-toggler {
        border: none;
    }

    .navbar-toggler:focus {
        box-shadow: none;
    }

    

    /* Dropdown animation */

        .dropdown-menu {
            animation: dropdownFade .15s ease;
            transform-origin: top;
        }

    @keyframes dropdownFade {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

</style>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">
        <a href="/admin/dashboard" class="navbar-brand text-decoration-none">
            <img src="{{ asset('images/Logo.png') }}"
                alt="ACCAPrep with Malasri"
                height="40">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="adminNavbar">
            <ul class="navbar-nav mb-2 mb-lg-0">

                <!-- Dashboard -->
                <li class="nav-item ms-3">
                    <a class="nav-link" href="/admin/dashboard">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>

                <!-- Academics Dropdown -->
                <li class="nav-item dropdown ms-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-mortarboard me-1"></i> Academics
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/admin/institutes">Institutes</a></li>
                        <li><a class="dropdown-item" href="/admin/batches">Batches</a></li>
                        <li><a class="dropdown-item" href="/admin/students">Students</a></li>
                        <li><a class="dropdown-item" href="/admin/papers">Papers</a></li>
                    </ul>
                </li>

                <!-- Tests Dropdown -->
                <li class="nav-item dropdown ms-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-ui-checks-grid me-1"></i> Tests
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/admin/mock-tests">All Tests</a></li>
                        <li><a class="dropdown-item" href="/admin/mock-tests/create">Add New Test</a></li>
                    </ul>
                </li>

                <!-- Question Bank Dropdown -->
                <li class="nav-item dropdown ms-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-journal-text me-1"></i> Question Bank
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/admin/questions">All Questions</a></li>
                        <li><a class="dropdown-item" href="/admin/questions/create">Add New Question</a></li>
                    </ul>
                </li>

                <!-- Reports -->
                <li class="nav-item ms-3">
                    <a class="nav-link" href="{{ route('admin.reports.index') }}">
                        <i class="bi bi-clipboard-data me-1"></i></i> Reports
                    </a>
                </li>

                <!-- Logout -->
           <li class="nav-item ms-3 d-flex align-items-center">
                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline m-0 p-0">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link btn-logout py-2 px-0">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </li>



            </ul>
        </div>
    </div>
</nav>

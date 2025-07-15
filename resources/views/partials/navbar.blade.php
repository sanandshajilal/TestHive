<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .navbar .nav-link:hover {
        color: #ffc107 !important;
    }

    .navbar .dropdown-menu a:hover {
        background-color: #343a40;
    }

    .navbar .btn-logout {
        border: none;
        background: transparent;
        color: rgba(255, 255, 255, 0.55);
        padding: 0.5rem 1rem;
        padding-top: 0.5rem;
        cursor: pointer;
    }

    .navbar .btn-logout:hover {
        color: #ffc107 !important;
    }

</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
<a class="navbar-brand fw-bold text-warning" href="/admin/dashboard">TestHive</a>

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
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="/admin/institutes">Institutes</a></li>
                        <li><a class="dropdown-item" href="/admin/batches">Batches</a></li>
                        <li><a class="dropdown-item" href="/admin/papers">Papers</a></li>
                    </ul>
                </li>

                <!-- Tests Dropdown -->
                <li class="nav-item dropdown ms-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-ui-checks-grid me-1"></i> Tests
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="/admin/mock-tests">All Tests</a></li>
                        <li><a class="dropdown-item" href="/admin/mock-tests/create">Add New Test</a></li>
                    </ul>
                </li>

                <!-- Question Bank Dropdown -->
                <li class="nav-item dropdown ms-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-journal-text me-1"></i> Question Bank
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="/admin/questions">All Questions</a></li>
                        <li><a class="dropdown-item" href="/admin/questions/create">Add New Question</a></li>
                    </ul>
                </li>

                <!-- Reports -->
                <li class="nav-item ms-3">
                    <a class="nav-link" href="{{ route('admin.reports.index') }}">
                        <i class="bi bi-bar-chart-line me-1"></i> Reports
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'ACCAPrep with Malasri')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield('styles')
</head>

<style>
    body {
        background-color: #f7f8fa;
        background-image:
            radial-gradient(#e9ecef 1px, transparent 1px);
        background-size: 24px 24px;
    }

 .card,
.card-style,
.header-box {
    border: none;
    border-radius: 16px;
    box-shadow:
        0 4px 12px rgba(0,0,0,0.04),
        0 12px 32px rgba(78,115,223,0.08);
}

</style>
<body>
    @include('partials.navbar')

    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS CDN (Optional for dropdowns, modals etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>


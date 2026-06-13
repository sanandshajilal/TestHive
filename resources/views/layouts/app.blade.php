<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'ACCAPrep with Malasri')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ACCA Mock Test Portal by S. Malasri">
    <meta property="og:title" content="ACCAPrep with Malasri - Admin Access">
    <meta property="og:description" content="Access ACCA mock tests, practice exams and assessments.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://testhive-fm8r.onrender.com/">

    <meta property="og:image" content="https://testhive-fm8r.onrender.com/images/accaprep-preview.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="ACCAPrep with Malasri">
    <meta name="twitter:description" content="Professional ACCA Mock Test Portal">
    <meta name="twitter:image" content="https://testhive-fm8r.onrender.com/images/accaprep-preview.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
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


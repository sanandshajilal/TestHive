<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ACCAPrep with Malasri</title>
<meta name="description" content="ACCA Mock Test Portal by S. Malasri">

<meta property="og:title" content="ACCAPrep with Malasri">
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

<style>
body {
    background-color: #f7f8fa;
    background-image:
        radial-gradient(#e9ecef 1px, transparent 1px);
    background-size: 24px 24px;
}
.login-container {
    max-width: 560px;
    margin: 60px auto;
    background-color: #ffffff;
    border-radius: 18px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}
.top-bar {
    height: 6px;
    background-color: #832b00   ;
}
.header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.2rem 1.5rem 0 1.5rem;
}
.header-left {
    font-size: 1.2rem;
    font-weight: bold;
    color: #343a40;
    letter-spacing: 0.5px;
}
.header-right {
    font-weight: bold;
    font-size: 1.2rem;
    color: #832b00;
    display: flex;
    align-items: center;
}
.header-right i {
    font-size: 0.5rem;
    margin-right: 6px;
    color: #facc15;
}
.divider {
    border-top: 1px solid #e5e7eb;
    margin: 0 1.5rem 1rem 1.5rem;
}
.title-highlight {
    background-color: #f7e3d8;
    color: #9a5631;
    font-weight: 600;
    text-align: center;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    text-transform: uppercase;
    font-size: 0.95rem;
    letter-spacing: 1px;
    margin: 1.2rem 2rem 1.5rem 2rem;
}
.form-section {
    padding: 0 1.5rem 1.5rem 1.5rem;
}
.form-label {
    font-weight: 500;
    color: #333;
}

.form-control:focus,
.form-select:focus {
    border-color: #b46e4c;
    box-shadow: 0 0 0 0.2rem rgba(180,110,76,0.15);
}

.btn-primary {
    background-color: #b46e4c;
    border-color: #b46e4c;
    transition: all 0.2s ease;
}
.btn-primary:hover {
    background-color: #832b00;
    border-color: #832b00;
}

.btn-primary:focus,
.btn-primary:active,
.btn-primary.active,
.btn-primary:focus-visible {
    background-color: #832b00 !important;
    border-color: #832b00 !important;
    box-shadow: none !important;
}

.btn-primary:not(:disabled):not(.disabled):active {
    background-color: #832b00 !important;
    border-color: #832b00 !important;
}

.disclaimer {
    font-size: 0.85rem;
    color: #6c757d;
    text-align: center;
    margin-top: 30px;
}
.error-box {
    margin-bottom: 1rem;
}
.alert-icon {
    margin-right: 8px;
    font-size: 1.2rem;
    vertical-align: middle;
}

.brand-logo {
    line-height: 1.1;
    margin-bottom: 10px;
}
.brand-subtext {
    font-size: 0.6rem;
    color:rgba(169, 169, 169, 0.86);
    margin-left: 1.5rem;
}

.btn-primary:disabled,
.btn-primary.disabled {
    background-color: #b46e4c !important;
    border-color: #b46e4c !important;
    opacity: 0.85;
}



</style>
</head>
<body>

<div class="container">
    <div class="login-container">
        <div class="top-bar"></div>
        <div class="header-row">
            <div class="header-left">MOCK TEST PORTAL</div>
           <div class="brand-logo d-flex flex-column">
                <div class="header-right">
                    <img src="{{ asset('images/logo.png') }}"
                        alt="ACCAPrep by Malasri"
                        height="55">
                </div>
            </div>
        </div>
        <div class="divider"></div>
        <div class="title-highlight text-uppercase">Enter your details to access the test</div>

        <div class="form-section">
            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center error-box">
                    <i class="bi bi-exclamation-circle-fill alert-icon text-danger"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('student.validateAccess') }}" onsubmit="this.querySelector('button[type=submit]').disabled = true;">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger small"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="text-danger small"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                    @enderror
                </div>

                <!-- Institute and Batch in one row -->
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">Institute</label>
                        <select name="institute_id" id="institute" class="form-select" required>
                            <option value="" disabled selected>Select Institute</option>
                            @foreach($institutes as $institute)
                                <option value="{{ $institute->id }}" {{ old('institute_id') == $institute->id ? 'selected' : '' }}>{{ $institute->name }}</option>
                            @endforeach
                        </select>
                        @error('institute_id')
                            <div class="text-danger small"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">Batch</label>
                        <select name="batch_id" id="batch" class="form-select" required>
                            <option value="" disabled selected>Select Batch</option>
                        </select>
                        @error('batch_id')
                            <div class="text-danger small"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

               <!-- Access Code -->
                <div class="mb-3">
                    <label class="form-label">Access Code</label>
                    <input type="text" name="access_code" class="form-control" value="{{ old('access_code') }}" required>

                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        Use the access code provided. Access is available only for active tests.
                    </div>

                    @error('access_code')
                        <div class="text-danger small">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Proceed</button>
            </form>
        </div>
    </div>
</div>

<div class="disclaimer">
    <div>© 2025 ACCAPrep with Malasri</div>
    
</div>

<!-- Batch Dropdown Script -->
<script>
    const allBatches = @json($batches);
    const oldBatch = "{{ old('batch_id') }}";
    const oldInstitute = "{{ old('institute_id') }}";

    function populateBatches(instituteId) {
        const batchSelect = document.getElementById('batch');
        batchSelect.innerHTML = '<option value="" disabled selected>Select Batch</option>';
        allBatches.forEach(batch => {
            if (batch.institute_id == instituteId) {
                const option = document.createElement('option');
                option.value = batch.id;
                option.text = batch.name;
                if (batch.id == oldBatch) {
                    option.selected = true;
                }
                batchSelect.appendChild(option);
            }
        });
    }

    document.getElementById('institute').addEventListener('change', function () {
        populateBatches(this.value);
    });
    if (oldInstitute) {
        populateBatches(oldInstitute);
    }
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/bootstrap.bundle.min.js"></script>
</body>
</html>

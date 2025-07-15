@extends('layouts.app')

@section('title', 'Reports')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<style>
    body {
        background-color: #f9fafb;
    }

    .header-box {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
    }

    .card-style {
        border-radius: 1rem;
        background: #fff;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        margin-bottom: 1.5rem;
    }

    .summary-label {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .summary-value {
        font-weight: 600;
        color: #212529;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .dataTables_wrapper .dt-buttons {
        margin-bottom: 1rem;
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        border-radius: 50px !important;
        font-size: 0.8rem;
        padding: 0.35rem 0.75rem;
    }

    @media (max-width: 768px) {
        .dataTables_wrapper .dt-buttons {
            flex-wrap: wrap;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Header Box -->
    <div class="header-box mb-4">
        <h5 class="fw-semibold text-dark mb-0">📈 Reports Dashboard</h5>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card-style">
                <div class="summary-label">Total Mock Tests</div>
                <div class="summary-value">12</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card-style">
                <div class="summary-label">Students Attempted</div>
                <div class="summary-value">146</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card-style">
                <div class="summary-label">Average Score</div>
                <div class="summary-value">62%</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card-style">
                <div class="summary-label">Top Scoring Test</div>
                <div class="summary-value">F5 Mock 2</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#testwise">Test-wise</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#studentwise">Student-wise</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#questionanalysis">Question Analysis</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Test-wise Report -->
        <div class="tab-pane fade show active" id="testwise">
            <div class="card-style">
                <h5 class="fw-semibold mb-3 text-primary">📊 Test-wise Report</h5>
                <div class="table-responsive">
                    <table id="testwiseTable" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Test Name</th>
                                <th>Paper</th>
                                <th>Students</th>
                                <th>Average</th>
                                <th>Highest</th>
                                <th>Lowest</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>F5 Mock 1</td>
                                <td>F5</td>
                                <td>30</td>
                                <td>61%</td>
                                <td>92%</td>
                                <td>28%</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">View Attempts</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Student-wise Report -->
        <div class="tab-pane fade" id="studentwise">
            <div class="card-style">
                <h5 class="fw-semibold mb-3 text-primary">👩‍🎓 Student-wise Report</h5>
                <div class="table-responsive">
                    <table id="studentwiseTable" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Institute</th>
                                <th>Batch</th>
                                <th>Tests Taken</th>
                                <th>Average</th>
                                <th>Best</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>John Mathew</td>
                                <td>Alpha Institute</td>
                                <td>Batch A</td>
                                <td>5</td>
                                <td>68%</td>
                                <td>85%</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">View Responses</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Question Analysis -->
        <div class="tab-pane fade" id="questionanalysis">
            <div class="card-style">
                <h5 class="fw-semibold mb-3 text-primary">🔍 Question Analysis</h5>
                <div class="table-responsive">
                    <table id="questionTable" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Question</th>
                                <th>Paper</th>
                                <th>Topic</th>
                                <th>Difficulty</th>
                                <th>Accuracy</th>
                                <th>Attempts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>What is the contribution margin ratio?</td>
                                <td>F2</td>
                                <td>CVP Analysis</td>
                                <td>Medium</td>
                                <td>42%</td>
                                <td>28</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    $(document).ready(function () {
        $('#testwiseTable, #studentwiseTable, #questionTable').DataTable({
            dom: 'Bfrtip',
            pageLength: 10,
            buttons: [
                { extend: 'copy', className: 'btn btn-sm btn-outline-secondary' },
                { extend: 'excel', className: 'btn btn-sm btn-outline-success' },
                { extend: 'pdf', className: 'btn btn-sm btn-outline-danger' },
                { extend: 'print', className: 'btn btn-sm btn-outline-dark' }
            ]
        });
    });
</script>
@endsection

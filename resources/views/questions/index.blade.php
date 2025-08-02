@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <style>
        body {
            background-color: #f9fafb;
        }

        .card-style {
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            background-color: #fff;
            padding: 1.5rem;
        }

        .table thead {
            background-color: #f1f3f5;
        }

        td {
            white-space: normal !important;
            word-wrap: break-word;
        }

        td.question-col {
            max-width: 800px;
            min-width: 400px;
        }

        .btn-sm {
            padding: 0.35rem 0.6rem;
            font-size: 0.8rem;
        }

        .table-responsive {
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .dataTables_wrapper .dt-buttons {
            margin-bottom: 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .dataTables_wrapper .dt-buttons .btn,
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.5rem !important;
        }

        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
            margin-top: 0.5rem;
        }

        .header-box {
            background-color: #ffffff;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 0.25rem;
        }

        .btn-soft-warning {
            background-color: #fff3cd;
            color: #856404;
            border: none;
        }

        .btn-soft-danger {
            background-color: #fdecea;
            color: #dc3545;
            border: none;
        }
        .btn-soft-info {
                background-color: #d1ecf1; /* light blue */
                color: #0c5460;            /* dark teal/blue for contrast */
                border: none;
            }

        .btn-soft-warning:hover,
        .btn-soft-danger:hover,
        .btn-soft-info:hover {
            opacity: 0.85;
        }
        
        .btn-sm.btn-soft-warning,
        .btn-sm.btn-soft-danger,
        .btn-sm.btn-soft-info {
            padding: 0.35rem 0.6rem;
            font-size: 0.8rem;
            min-width: 30px;
            height: 30px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
        }

            .question-col table {
                border-collapse: collapse;
                border: 1px solid #ccc;
                width: auto;
                margin-top: 0.5rem;
                margin-bottom: 0.5rem;
            }

            .question-col th,
            .question-col td {
                border: 1px solid #ccc;
                padding: 6px 10px;
                font-size: 0.9rem;
                text-align: left;
            }

            .question-col th {
                background-color: #f8f9fa;
                font-weight: 600;
            }



        @media screen and (max-width: 768px) {
            .dataTables_wrapper .dataTables_filter {
                float: none;
                text-align: left;
            }

            .dataTables_wrapper .dt-buttons {
                justify-content: start;
            }
        }
    </style>
@endsection

@section('content')
<div class="container py-4">
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h4 class="fw-semibold text-dark mb-0">All Questions</h4>
        <a href="{{ route('questions.create') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-circle me-1"></i> Add Question
        </a>
    </div>

    <div class="card-style">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-3">
                <select id="paper_id" class="form-select">
                    <option value="">All Papers</option>
                    @foreach ($papers as $paper)
                        <option value="{{ $paper->id }}">{{ $paper->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select id="topic_id" class="form-select">
                    <option value="">All Topics</option>
                </select>
            </div>

            <div class="col-md-3">
                <select id="sub_topic_id" class="form-select">
                    <option value="">All Subtopics</option>
                </select>
            </div>

            <div class="col-md-3">
                <select id="filterType" class="form-select">
                    <option value="">All Types</option>
                    <option value="mcq">MCQ</option>
                    <option value="multiple_select">Multiple Select</option>
                    <option value="one_word">One Word</option>
                    <option value="table_mcq">Table MCQ</option>
                    <option value="drag_and_drop">Drag and Drop</option>
                    <option value="dropdown">Drop Down List</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="questionsTable">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>#</th>
                        <th>Question</th>
                        <th>Paper</th>
                        <th>Topic</th>
                        <th>Subtopic</th>
                        <th>Type</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $question)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="question-col">{!! $question->question_text !!}</td>
                            <td data-id="{{ $question->paper->id ?? '' }}">{{ $question->paper->name ?? 'N/A' }}</td>
                            <td data-id="{{ $question->topic->id ?? '' }}">{{ $question->topic->name ?? 'N/A' }}</td>
                            <td data-id="{{ $question->subTopic->id ?? '' }}">{{ $question->subTopic->name ?? 'N/A' }}</td>
                            <td data-type="{{ $question->question_type }}">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-sm btn-soft-info" onclick="previewQuestion({{ $question->id }})" title="Preview">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-sm btn-soft-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Delete this question?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-soft-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

{{-- Question Preview Modal --}}
<div class="modal fade" id="questionPreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Question Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="questionPreviewContent">
        <p class="text-muted">Loading...</p>
      </div>
    </div>
  </div>
</div>
{{-- Question Preview Modal --}}
<div class="modal fade" id="questionPreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Question Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="questionPreviewContent">
        <p class="text-muted">Loading...</p>
      </div>
    </div>
  </div>
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function () {
        var table = $('#questionsTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    className: 'btn btn-outline-secondary btn-sm rounded-pill',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-outline-success btn-sm rounded-pill',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-outline-danger btn-sm rounded-pill',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'print',
                    className: 'btn btn-outline-dark btn-sm rounded-pill',
                    exportOptions: { columns: ':not(:last-child)' }
                }
            ]
        });

        // Push filtering logic
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const selectedPaperId = $('#paper_id').val();
            const selectedTopicId = $('#topic_id').val();
            const selectedSubTopicId = $('#sub_topic_id').val();
            const selectedType = $('#filterType').val().trim().toLowerCase();

            const row = table.row(dataIndex).node();
            const rowPaperId = row.querySelector('td:nth-child(3)').getAttribute('data-id');
            const rowTopicId = row.querySelector('td:nth-child(4)').getAttribute('data-id');
            const rowSubTopicId = row.querySelector('td:nth-child(5)').getAttribute('data-id');
            const rowType = row.querySelector('td:nth-child(6)').getAttribute('data-type');

            return (!selectedPaperId || selectedPaperId === rowPaperId)
                && (!selectedTopicId || selectedTopicId === rowTopicId)
                && (!selectedSubTopicId || selectedSubTopicId === rowSubTopicId)
                && (!selectedType || selectedType === rowType);
        });

        $('#paper_id, #topic_id, #sub_topic_id, #filterType').on('change', function () {
            table.draw();
        });

        $('#paper_id').on('change', function () {
            const paperId = $(this).val();
            $('#topic_id').html('<option value="">Loading...</option>');
            $('#sub_topic_id').html('<option value="">-- All Subtopics --</option>');

            if (paperId) {
                $.get('/api/topics-by-paper/' + paperId, function (data) {
                    let options = '<option value="">-- All Topics --</option>';
                    data.forEach(topic => {
                        options += `<option value="${topic.id}">${topic.name}</option>`;
                    });
                    $('#topic_id').html(options);
                });
            } else {
                $('#topic_id').html('<option value="">-- All Topics --</option>');
            }

            $('#sub_topic_id').html('<option value="">-- All Subtopics --</option>');
        });

        $('#topic_id').on('change', function () {
            const topicId = $(this).val();
            $('#sub_topic_id').html('<option value="">Loading...</option>');

            if (topicId) {
                $.get('/api/subtopics-by-topic/' + topicId, function (data) {
                    let options = '<option value="">-- All Subtopics --</option>';
                    data.forEach(sub => {
                        options += `<option value="${sub.id}">${sub.name}</option>`;
                    });
                    $('#sub_topic_id').html(options);
                });
            } else {
                $('#sub_topic_id').html('<option value="">-- All Subtopics --</option>');
            }
        });
    });

    function previewQuestion(id) {
    fetch(`/api/question-preview/${id}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById("questionPreviewContent").innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById("questionPreviewModal"));
            modal.show();
        });
}

</script>

<script src="{{ asset('js/question_form.js') }}"></script>
@endsection

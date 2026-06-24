@extends('layouts.app')


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    @section('styles')
<style>
body {
    background: #f9fafb;
}

/* ===================================
   HEADER
=================================== */

.header-box {
    position: relative;
    background: #fff;
    border-radius: 1rem;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 2px 10px rgba(180,110,76,.08);
    overflow: hidden;
}

.header-box::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: #832b00;
}

/* ===================================
   MAIN CARD
=================================== */

.card-style {
    background: #fff;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(180,110,76,.08);
}

/* ===================================
   SUMMARY CARDS
=================================== */

.summary-card {

    background: #fff;

    border: 1px solid #edd7ca;

    border-radius: 1rem;

    padding: 1rem 1.25rem;

    height: 100%;

    transition: all .25s ease;

    box-shadow: 0 2px 8px rgba(180,110,76,.08);

}

.summary-card:hover {

    border-color: #d7b29d;

    transform: translateY(-2px);

    box-shadow: 0 6px 16px rgba(180,110,76,.12);

}

.summary-label {

    font-size: .8rem;

    color: #9a5631;

    margin-bottom: .35rem;

}

.summary-value {

    font-size: 1.45rem;

    font-weight: 700;

    color: #1f2937;

}

/* ===================================
   FILTERS
=================================== */

.border-bottom {
    border-color: #edd7ca !important;
}

.form-control,
.form-select {

    border-radius: .75rem;

    border: 1px solid #d9d9d9;

}

.form-control:focus,
.form-select:focus {

    border-color: #b46e4c;

    box-shadow: 0 0 0 .2rem rgba(180,110,76,.15);

}

/* ===================================
   BUTTONS
=================================== */

.btn-primary {

    background: #b46e4c;

    border-color: #b46e4c;

}

.btn-primary:hover {

    background: #832b00;

    border-color: #832b00;

}

.btn-soft-info {

    background: #e8f3f3;

    color: #2f6d73;

    border: none;

}

.btn-soft-info:hover {

    background: #2f6d73;

    color: #fff;

}

.btn-soft-warning {

    background: #f7e3d8;

    color: #832b00;

    border: none;

}

.btn-soft-warning:hover {

    background: #b46e4c;

    color: #fff;

}

.btn-soft-danger {

    background: #fdecea;

    color: #c0392b;

    border: none;

}

.btn-soft-danger:hover {

    background: #c0392b;

    color: #fff;

}

.btn-sm.btn-soft-info,
.btn-sm.btn-soft-warning,
.btn-sm.btn-soft-danger {

    width: 32px;

    height: 32px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

}

/* ===================================
   ACTION BUTTONS
=================================== */

.action-buttons {

    display: flex;

    justify-content: center;

    gap: .35rem;

}

/* ===================================
   TABLE
=================================== */

.table-responsive {

    border-radius: .75rem;

    overflow: hidden;

}

.table thead {

    background: #fcf7f3;

}

.table thead th {

    color: #9a5631;

    font-weight: 600;

    border: none;

    padding: .9rem .75rem;

}

#questionsTable {

    border-collapse: separate;

    border-spacing: 0 10px;

}

/* ===================================
   ROW CARD STYLE
=================================== */

#questionsTable tbody tr {

    background: #fff;

    transition: all .2s ease;

}

#questionsTable tbody tr:hover {

    transform: translateY(-2px);

    box-shadow: 0 6px 16px rgba(180,110,76,.08);

}

#questionsTable tbody td {

    background: #fff;

    border-top: 1px solid #edd7ca;

    border-bottom: 1px solid #edd7ca;

    border-left: none;

    border-right: none;

    padding: 1rem .9rem;

    vertical-align: top;

}

/* subtle column separation */

#questionsTable tbody td + td {

    box-shadow: inset 1px 0 0 rgba(180,110,76,.06);

}

/* first column */

#questionsTable tbody td:first-child {

    border-left: 1px solid #edd7ca;

    border-radius: 14px 0 0 14px;

    width: 70px;

    text-align: center;

    vertical-align: middle;

}

/* last column */

#questionsTable tbody td:last-child {

    border-right: 1px solid #edd7ca;

    border-radius: 0 14px 14px 0;

    vertical-align: middle;

}

/* accent on hover */

#questionsTable tbody tr:hover td:first-child {

    border-left: 3px solid #b46e4c;

}

/* ===================================
   QUESTION NUMBER
=================================== */

.question-index {

    width: 70px;

}

.question-number {

    width: 34px;

    height: 34px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #f7e3d8;

    color: #832b00;

    font-size: .85rem;

    font-weight: 700;

    transition: .2s;

}

#questionsTable tbody tr:hover .question-number {

    background: #b46e4c;

    color: #fff;

}

/* ===================================
   QUESTION COLUMN
=================================== */

td.question-col {

    max-width: 850px;

    min-width: 420px;

    line-height: 1.7;

    color: #374151;

    font-size: .98rem;

}

.question-col p:last-child {

    margin-bottom: 0;

}

.question-col table {

    border-collapse: collapse;

    margin-top: .6rem;

    border: 1px solid #edd7ca;

}

.question-col table th,
.question-col table td {

    border: 1px solid #edd7ca;

    padding: 6px 10px;

    font-size: .9rem;

}

.question-col table th {

    background: #fcf7f3;

    color: #9a5631;

    font-weight: 600;

}

/* ===================================
   CENTER CATEGORY COLUMNS
=================================== */

#questionsTable tbody td:nth-child(3),
#questionsTable tbody td:nth-child(4),
#questionsTable tbody td:nth-child(5),
#questionsTable tbody td:nth-child(6) {

    text-align: center;

    vertical-align: middle;

}

/* ===================================
   Meta Dots
=================================== */

.meta-item{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:6px;

    font-size:.88rem;
    color:#495057;
    font-weight:500;
}



.paper-meta{
    color:#6c757d;
    font-weight:500;
}

.topic-meta{
    color:#6c757d;
    font-weight:500;
}

.subtopic-meta{
    color:#6c757d;
    font-weight:500;
}

.type-meta{
    color:#6c757d;
    font-size:.82rem;
    letter-spacing:.04em;
    text-transform:uppercase;
}


/* ===================================
   DATATABLES
=================================== */

.dataTables_wrapper .dt-buttons {

    display: flex;

    flex-wrap: wrap;

    gap: .5rem;

    margin-bottom: 1rem;

}

.dataTables_wrapper .dt-buttons .btn {

    border-radius: 999px !important;

}

.dataTables_wrapper .dataTables_filter {

    margin-top: .5rem;

    margin-bottom: 1rem;

}

.dataTables_wrapper .dataTables_filter input {

    border-radius: .75rem !important;

    border: 1px solid #d9d9d9 !important;

    padding: .4rem .8rem !important;

}

.dataTables_wrapper .dataTables_filter input:focus {

    border-color: #b46e4c !important;

    box-shadow: 0 0 0 .2rem rgba(180,110,76,.15) !important;

}

/* ===================================
   MOBILE
=================================== */

@media(max-width:768px){

    td.question-col{

        min-width:260px;

    }

    .dataTables_wrapper .dt-buttons{

        justify-content:flex-start;

    }

}
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-semibold text-dark mb-0">
                <i class="bi bi-collection me-2" style="color:#832b00;"></i>
                Question Bank
            </h4>

            <small class="text-muted">
                Manage and organize questions across all papers and topics.
            </small>
        </div>
        <a href="{{ route('questions.create') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-circle me-1"></i> Add Question
        </a>
    </div>

        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Total Questions</div>
                    <div class="summary-value">{{ $questions->count() }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Papers</div>
                    <div class="summary-value">{{ $papers->count() }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Topics</div>
                    <div class="summary-value">{{ $topics->count() ?? '-' }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="summary-card">
                    <div class="summary-label">Question Types</div>
                    <div class="summary-value">6</div>
                </div>
            </div>

        </div>

    <div class="card-style">
        <!-- Filters -->
         <div class="border-bottom pb-2 mb-3">
            <h6 class="fw-semibold mb-1">
                <i class="bi bi-funnel me-2" style="color:#832b00;"></i>
                Filters
            </h6>

            <small class="text-muted">
                Narrow down questions by paper, topic, sub-topic and type.
            </small>
        </div>
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
                            <td class="question-index">
                                <span class="question-number">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td class="question-col">{!! str_replace('[blank]', '__________', $question->question_text) !!}</td>
                            <td class="text-center" data-id="{{ $question->paper->id ?? '' }}">
                                <span class="meta-item paper-meta">
                                    <span class="meta-dot"></span>
                                    {{ $question->paper->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-center" data-id="{{ $question->topic->id ?? '' }}">
                                <span class="meta-item topic-meta">
                                    <span class="meta-dot"></span>
                                    {{ $question->topic->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-center" data-id="{{ $question->subTopic->id ?? '' }}">
                                <span class="meta-item subtopic-meta">
                                    <span class="meta-dot"></span>
                                    {{ $question->subTopic->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-center" data-type="{{ $question->question_type }}">
                                <span class="meta-item type-meta">
                                    <span class="meta-dot"></span>
                                    {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                </span>
                            </td>
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

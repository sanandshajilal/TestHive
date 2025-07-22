<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TestHive by Malasri</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f7f8fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
            color: #4e73df;
        }

        .navbar-brand:hover {
            color: #3756c0;
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

        .icon-btn {
            background: none;
            border: none;
            color: #333;
            padding: 6px 12px;
            border-radius: 6px;
            transition: background-color 0.2s ease;
        }

        .icon-btn:hover {
            background-color: #f1f1f1;
        }

        .test-name-label {
            font-weight: 500;
            font-size: 0.95rem;
            color: #6c757d;
            margin: 0 12px;
            text-transform: uppercase;
        }

        .test-container {
            max-width: 900px;
            margin: 100px auto 40px;
            background: #fff;
            border-top: 4px solid #0d6efd;
            padding: 30px;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .question-header {
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }

        .question-text {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 25px;
        }

        .option {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .option:hover {
            background-color: #f5f9ff;
        }

        .selected-option {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }

        .option input[type="radio"], .option input[type="checkbox"] {
            margin-right: 10px;
        }

        .btn-nav {
            min-width: 100px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .question-count {
            color: #6c757d;
        }

        .flag-btn {
            border: none;
            background: none;
            color: #dc3545;
            cursor: pointer;
        }

        .table-mcq-table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-mcq-table th, .table-mcq-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: center;
        }
        .flagged {
            color: #dc3545 !important;
        }
        .brand-logo {
        line-height: 1.1;
            }

            .brand-subtext {
                font-size: 0.6rem;
                color: rgba(169, 169, 169, 0.86);
                margin-left: 1.7rem;
            }
               @media (max-width: 767.98px) {
                    .progress-label,
                    .help-label,
                    .exit-label {
                        display: none !important;
                    }

                    .progress-button i,
                    .help-button i,
                    .exit-button i {
                        margin-right: 0 !important;
                    }

                    .test-name-label {
                        display: none !important;
                    }
                }

                /* Force dropdown to a larger width using an id or !important */
                #progressDropdownMenu.dropdown-menu {
                    min-width: 300px !important;
                    max-width: 90vw;
                }

                .dropdown {
                    position: relative;
                }

                /* Flex layout for legend row */
                .legend-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: nowrap;
                    gap: 6px;
                    font-size: 0.85rem;
                    margin-bottom: 8px;
                }

                .legend-row span {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    white-space: nowrap;
                }

                /* Question number button spacing */
                #statusContainer .col {
                    padding-left: 4px;
                    padding-right: 4px;
                }
                #statusContainer button {
                    min-width: 4px;
                    padding: 0.25rem 0.5rem;
                }

                /* Responsive behavior for smaller screens */
                @media (max-width: 576px) {
                    #progressDropdownMenu {
                        width: 240px !important;
                    }

                    .legend-row {
                        flex-wrap: wrap;
                        gap: 6px;
                        font-size: 0.75rem;
                    }

                    #statusContainer {
                        --bs-columns: 5;
                    }
                }

                        
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom fixed-top py-2">
    <div class="container-fluid px-3">
        <div class="d-flex justify-content-between align-items-center w-100 flex-wrap">
            <!-- Brand -->
            <a class="navbar-brand brand-logo d-flex flex-column align-items-start text-decoration-none" href="#">
                <div class="d-flex align-items-center header-right fw-bold">
                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i>
                    TestHive
                </div>
                <div class="brand-subtext">by <strong>MALASRI</strong></div>
            </a>

            <!-- Timer + Progress + Help + Exit -->
            <div class="d-flex flex-wrap justify-content-end align-items-center gap-2 mt-2 mt-lg-0" style="flex: 1 1 auto;">
                <!-- Timer -->
                <div class="d-flex align-items-center text-primary fw-semibold timer-display">
                    <i class="bi bi-clock me-1"></i>
                    <span id="countdown">Loading...</span>
                </div>

               <!-- Progress Dropdown -->
               <div class="dropdown">
                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="progressDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bar-chart-fill me-1"></i> Progress
                </button>
                
                <ul id="progressDropdownMenu" class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="progressDropdown">
                    <li class="legend-row text-muted">
                        <span><span class="badge rounded-pill bg-success">&nbsp;</span> Answered</span>
                        <span><span class="badge rounded-pill bg-secondary">&nbsp;</span> Not Answered</span>
                        <span><span class="badge rounded-pill bg-primary">&nbsp;</span> Current</span>
                        <span><i class="bi bi-flag-fill text-danger"></i> Flagged</span>
                    </li>
                    <hr class="my-2">
                    <li>
                        <div id="statusContainer" class="row row-cols-5 row-cols-sm-6 row-cols-md-8 row-cols-lg-10 g-2" style="max-height: 250px; overflow-y: auto;">
                            <!-- Status buttons go inside here -->
                        </div>
                    </li>
                </ul>

            </div>




                <!-- Help Button -->
                <button class="btn btn-outline-secondary btn-sm help-button" data-bs-toggle="modal" data-bs-target="#helpModal">
                    <i class="bi bi-question-circle me-1"></i>
                    <span class="help-label">Help</span>
                </button>

                <!-- Divider -->
                <div style="width: 1px; height: 24px; background-color: #ccc;"></div>

                <!-- Test Title -->
                <span class="test-name-label">
                    <i class="bi bi-journal-text me-1 text-muted"></i>{{ $mockTest->title }}
                </span>

                <!-- Exit Button -->
                <form action="{{ route('student.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="exit-button btn btn-outline-danger btn-sm d-flex align-items-center">
                        <i class="bi bi-door-closed me-1"></i>
                        <span class="exit-label">Exit</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

    <div class="container">
        <div class="test-container">
            <div class="topbar">
                <div class="question-count">
                    Question {{ $questionNumber }} of {{ $totalQuestions }}
                </div>
             @php
                $isFlagged = $isFlagged ?? false;
            @endphp

            <button 
                id="flagButton" 
                class="flag-btn {{ $isFlagged ? 'flagged' : '' }}" 
                title="{{ $isFlagged ? 'Unflag this question' : 'Flag this question' }}"
                data-flagged="{{ $isFlagged ? '1' : '0' }}"
            >
                <i class="bi {{ $isFlagged ? 'bi-flag-fill' : 'bi-flag' }}"></i>
                <span class="flag-text">{{ $isFlagged ? 'Unflag' : 'Flag' }}</span>
            </button>



            </div>

            <div class="question-header">
                @if(!in_array($question->question_type, ['dropdown']))
                    <div class="question-text">{!! $question->question_text !!}</div>
                @endif

            </div>

            <form id="questionForm">
                @csrf
                <input type="hidden" name="question_id" value="{{ $question->id }}">
                <input type="hidden" name="next_question_number" value="{{ $questionNumber + 1 }}">
                <input type="hidden" name="is_last_question" value="{{ $questionNumber == $totalQuestions ? '1' : '0' }}">

                {{-- MCQ --}}
                @if($question->question_type == 'mcq')
                    @php
                        $options = is_array($question->options) ? $question->options : json_decode($question->options, true);
                    @endphp
                    @foreach($options as $key => $text)
                        <div class="option @if($selectedOption == $key) selected-option @endif"
                            style="cursor: pointer;" 
                            onclick="selectRadioOption('{{ $key }}')">
                            <input 
                                type="radio" 
                                name="answer" 
                                value="{{ $key }}" 
                                id="opt{{ $key }}" 
                                @if($selectedOption == $key) checked @endif
                            >
                            <label for="opt{{ $key }}" class="mb-0">{{ $text }}</label>
                        </div>
                    @endforeach

                {{-- Multiple Select --}}
                @elseif($question->question_type == 'multiple_select')
                    @php
                        $options = is_array($question->options) ? $question->options : json_decode($question->options, true);
                    @endphp
                    @foreach($options as $key => $text)
                        <div class="option">
                            <input 
                                type="checkbox" 
                                name="answer[]" 
                                value="{{ $key }}" 
                                id="chk{{ $key }}" 
                                @if(is_array($selectedOption) && in_array($key, $selectedOption)) checked @endif
                            >
                            <label for="chk{{ $key }}" class="mb-0">{{ $text }}</label>
                        </div>
                    @endforeach

                {{-- One Word --}}
                @elseif($question->question_type == 'one_word')
                    <div class="mb-3">
                        <label for="oneWordAnswer" class="form-label fw-semibold">Type your answer:</label>
                        <input 
                            type="text" 
                            name="answer" 
                            id="oneWordAnswer" 
                            class="form-control" 
                            value="{{ $selectedOption ?? '' }}" 
                            autocomplete="off"
                        >
                    </div>

                {{-- Table MCQ with dynamic labels --}}
                @elseif($question->question_type === 'table_mcq' && is_array($statements) && is_array($labels))
                    <table class="table-mcq-table table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Statement</th>
                                @foreach($labels as $label)
                                    <th class="text-center">{{ ucfirst($label) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statements as $index => $statement)
                                <tr>
                                    <td>{{ $statement }}</td>
                                    @foreach($labels as $label)
                                        <td class="text-center">
                                            <input
                                                type="radio"
                                                name="answer[{{ $index }}]"
                                                value="{{ strtolower($label) }}"
                                                @if(isset($selectedOption[$index]) && $selectedOption[$index] === strtolower($label)) checked @endif
                                            >
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                   {{-- DRAG AND DROP Question --}}
                    @elseif($question->question_type === 'drag_and_drop')
                    @php
                        $colA = $question->options['column_a'] ?? [];
                        $colB = $question->options['column_b'] ?? [];
                        $aLabel = $question->options['column_a_label'] ?? 'Column A';
                        $bLabel = $question->options['column_b_label'] ?? 'Column B';

                        // Previously selected option: [aIndex => bIndex]
                        $selectedMap = $selectedOption ?? [];

                        // Invert to [bIndex => aIndex] for rendering
                        $bToA = [];
                        foreach ($selectedMap as $a => $b) {
                            $bToA[$b] = $a;
                        }
                    @endphp

                    <div class="mb-4">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ $bLabel }}</th>
                                        <th>{{ $aLabel }} (Drop Here)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($colB as $bIndex => $target)
                                        <tr>
                                            <td class="fw-medium">{{ $target }}</td>
                                            <td>
                                                <div class="dropzone border rounded p-2 text-muted"
                                                    ondrop="drop(event, {{ $bIndex }})"
                                                    ondragover="allowDrop(event)"
                                                    id="drop-{{ $bIndex }}">
                                                    @if(isset($bToA[$bIndex]))
                                                        @php $aIdx = $bToA[$bIndex]; @endphp
                                                        <div class="draggable bg-primary text-white rounded px-3 py-2"
                                                            draggable="true"
                                                            ondragstart="drag(event)"
                                                            id="drag-{{ $aIdx }}"
                                                            data-value="{{ $aIdx }}">
                                                            {{ $colA[$aIdx] ?? '' }}
                                                        </div>
                                                    @else
                                                        Drop here
                                                    @endif
                                                </div>
                                                <input type="hidden" name="student_answer[{{ $bIndex }}]" id="drop-input-{{ $bIndex }}" value="{{ $bToA[$bIndex] ?? '' }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <h6 class="mb-2">Draggable Options ({{ $aLabel }})</h6>
                            <div class="d-flex flex-wrap gap-2" id="drag-options">
                                @foreach($colA as $index => $item)
                                    @if(!in_array($index, $bToA))
                                        <div class="draggable bg-primary text-white rounded px-3 py-2"
                                            draggable="true"
                                            ondragstart="drag(event)"
                                            id="drag-{{ $index }}"
                                            data-value="{{ $index }}">
                                            {{ $item }}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <script>
                        function allowDrop(ev) {
                            ev.preventDefault();
                        }

                        function drag(ev) {
                            ev.dataTransfer.setData("text", ev.target.id);
                        }

                        function drop(ev, dropIndex) {
                            ev.preventDefault();
                            const draggedId = ev.dataTransfer.getData("text");
                            const draggedEl = document.getElementById(draggedId);
                            const dropZone = document.getElementById("drop-" + dropIndex);
                            const inputField = document.getElementById("drop-input-" + dropIndex);

                            // Clear old dropzone (if dragged item is already dropped elsewhere)
                            document.querySelectorAll('.dropzone').forEach(zone => {
                                if (zone.contains(draggedEl)) {
                                    const zoneIndex = zone.id.split('-')[1];
                                    zone.innerHTML = 'Drop here';
                                    document.getElementById("drop-input-" + zoneIndex).value = '';
                                }
                            });

                            // Move dragged item to new drop zone
                            dropZone.textContent = '';
                            dropZone.appendChild(draggedEl);
                            inputField.value = draggedEl.dataset.value;
                        }
                    </script>


                    @elseif($question->question_type == 'dropdown')
                    @php
                        $sentence = $question->question_text; // e.g., "Captain of [blank] is MS Dhoni in [blank]."
                        $dropdowns = is_array($question->options) ? $question->options : json_decode($question->options, true);
                        $selected = is_array($selectedOption) ? $selectedOption : [];
                    @endphp

                    <p><strong>Fill in the blanks:</strong></p>

                    <div class="mb-3">
                        @php
                            $parts = explode('[blank]', $sentence);
                        @endphp

                        @for ($i = 0; $i < count($parts); $i++)
                            {!! $parts[$i] !!}

                            @if(isset($dropdowns[$i]) && is_array($dropdowns[$i]['options']))
                                <select name="dropdown_answers[{{ $i }}]" class="form-select d-inline w-auto mx-1">
                                    <option value="">-- {{ $dropdowns[$i]['label'] ?? 'Select' }} --</option>
                                    @foreach($dropdowns[$i]['options'] as $opt)
                                        <option value="{{ $opt }}" @if(isset($selected[$i]) && $selected[$i] == $opt) selected @endif>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            @endif
                        @endfor
                    </div>



                {{-- Fallback --}}
                @else
                    <p class="text-muted fst-italic">This question type is currently not supported.</p>
                @endif

                {{-- Navigation Buttons --}}
                <div class="d-flex justify-content-between mt-4">
                    {{-- Previous Button --}}
                    <a 
                        href="{{ $questionNumber > 1 ? route('student.test', [$mockTest->id, $questionNumber - 1]) : '#' }}" 
                        class="btn btn-outline-secondary btn-nav save-time @if($questionNumber == 1) disabled @endif"
                    >
                        Previous
                    </a>

                    {{-- Save & Next / Submit Button --}}
                    @if($questionNumber < $totalQuestions)
                        <button type="submit" class="btn btn-primary btn-nav">
                            Save & Next
                        </button>
                    @else
                        <button type="submit" class="btn btn-success btn-nav">
                            Final Submit
                        </button>
                    @endif
                </div>
            </form>

{{-- Status Message --}}
<div id="saveStatus" class="mt-3"></div>

        </div>
    </div>

    
  <!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="max-height: 80vh;">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">
                    <i class="bi bi-question-circle me-2"></i>Help
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body overflow-auto">
                <div class="mb-4">
                    <h6><i class="bi bi-clock me-1 text-primary"></i> Timer</h6>
                    <p>Your remaining time is shown at the top (<strong class="text-primary">MM:SS</strong>). The test auto-submits when time runs out.</p>
                </div>

                <div class="mb-4">
                    <h6><i class="bi bi-box-arrow-right me-1 text-primary"></i> Navigation</h6>
                    <ul class="mb-0">
                        <li><strong>Previous:</strong> Go back to the previous question without saving changes.</li>
                        <li><strong>Save & Next:</strong> Save your current answer and move to the next question. <span class="text-danger">Only saved answers are considered for evaluation.</span></li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h6><i class="bi bi-flag me-1 text-danger"></i> Flag / Unflag</h6>
                    <p>Click the <i class="bi bi-flag text-danger"></i> button to mark a question for review. Once flagged, the icon becomes <i class="bi bi-flag-fill text-danger"></i>. Click again to unflag.</p>
                </div>

                <div class="mb-4">
                    <h6><i class="bi bi-bar-chart-fill me-1 text-primary"></i> Progress Dropdown</h6>
                    <p>The <span class="badge bg-primary">Progress</span> dropdown at the top shows your question status:</p>
                    <ul class="mb-0">
                        <li><span class="badge bg-success">●</span> Answered</li>
                        <li><span class="badge bg-secondary">●</span> Not Answered</li>
                        <li><span class="badge bg-primary">●</span> Current</li>
                        <li><i class="bi bi-flag-fill text-danger"></i> Flagged</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h6><i class="bi bi-check2-square me-1 text-success"></i> Final Submission</h6>
                    <p>Once you reach the last question, a summary screen will show counts of answered, flagged, and unanswered questions. You can submit from there. <strong class="text-danger">After submission, no changes can be made.</strong></p>
                </div>

                <div class="mb-4">
                    <h6><i class="bi bi-journal-text me-1 text-info"></i> Question Types</h6>
                    <ul class="mb-0">
                        <li><strong>MCQ:</strong> Select one correct option.</li>
                        <li><strong>Multiple Select:</strong> Select all correct options. Full marks are given only if all correct choices are selected.</li>
                        <li><strong>One Word:</strong> Type a single word or number as your answer.</li>
                        <li><strong>Table MCQ:</strong> Choose either <strong>Debit</strong> or <strong>Credit</strong> for each row in a table format.</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h6><i class="bi bi-info-circle me-1 text-secondary"></i> Test Overview</h6>
                    <ul class="mb-0">
                        <li><strong>Total Questions:</strong> {{ $mockTest->questions->count() }}</li>
                        <li><strong>Total Marks:</strong> {{ $mockTest->questions->sum('marks') }}</li>
                        <li><strong>Total Duration:</strong> {{ $mockTest->duration_minutes }} minutes</li>
                    </ul>
                </div>

                <div class="alert alert-info mb-0">
                    <strong>Answers are saved</strong> only when you click <strong>Save & Next</strong>.
                </div>
            </div>
        </div>
    </div>
</div>




    <!-- Final Submission Modal -->
<div class="modal fade" id="finalSubmitModal" tabindex="-1" aria-labelledby="finalSubmitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-3 shadow border-0">
      <div class="modal-header">
        <h5 class="modal-title" id="finalSubmitModalLabel">Final Submission</h5>
      </div>
      <div class="modal-body">
        <p class="mb-3 fw-bold">Review your test status before submitting:</p>
        <div class="d-grid gap-2">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Answered</span> 
                    <span class="badge bg-success rounded-pill" id="answeredCount">3</span>
                </div>
                <div class="d-flex justify-content-between align-items-center ms-3 text-muted">
                    <span>➕ Flagged</span> 
                    <span class="badge bg-warning text-dark rounded-pill" id="answeredFlaggedCount">1</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span>Not Answered</span> 
                    <span class="badge bg-secondary rounded-pill" id="unansweredCount">2</span>
                </div>
                <div class="d-flex justify-content-between align-items-center ms-3 text-muted">
                    <span>➕ Flagged</span> 
                    <span class="badge bg-danger rounded-pill" id="unansweredFlaggedCount">1</span>
                </div>
                </div>

        <hr>
        <p class="text-muted">Are you sure you want to submit your test?</p>
      </div>
      <div class="modal-footer border-0">
        <button id="cancelFinalSubmit" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="confirmFinalSubmit" class="btn btn-success">Submit</button>
      </div>
    </div>
  </div>
</div>

  <!-- ✅ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- ✅ Initialize Timer and Auto-Submit -->
<script>
    function selectRadioOption(optionId) {
        document.querySelectorAll('.option').forEach(el => el.classList.remove('selected-option'));
        const selected = document.getElementById('opt' + optionId);
        selected.checked = true;
        selected.parentElement.classList.add('selected-option');
    }

    let totalSeconds = {{ $remainingSeconds ?? ($mockTest->duration_minutes * 60) }};
    const countdownEl = document.getElementById('countdown');

    function formatTime(sec) {
        const m = String(Math.floor(sec / 60)).padStart(2, '0');
        const s = String(sec % 60).padStart(2, '0');
        return `${m}:${s}`;
    }

    function updateCountdown() {
        if (totalSeconds <= 0) {
            clearInterval(countdownInterval);
            countdownEl.innerText = '00:00';

            // Disable all controls
            document.querySelectorAll('input, textarea, select, button').forEach(el => {
                el.disabled = true;
            });

            // Optional: block pointer events
            document.body.style.pointerEvents = 'none';

            // Show overlay and auto-submit
            document.body.insertAdjacentHTML('beforeend', `
                <div id="autoSubmitOverlay" style="
                    position: fixed;
                    top: 0; left: 0; right: 0; bottom: 0;
                    background: rgba(0,0,0,0.5);
                    z-index: 9999;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    color: white;
                    font-size: 1.2rem;
                    text-align: center;
                ">
                    <div>
                        <div class="spinner-border text-light mb-3" role="status"></div><br>
                        Time is up! Submitting your test in <span id="autoSubmitSeconds">5</span> seconds...
                    </div>
                </div>
            `);

            let countdown = 5;
            const display = document.getElementById('autoSubmitSeconds');
            const interval = setInterval(() => {
                countdown--;
                display.textContent = countdown;
                if (countdown <= 0) {
                    clearInterval(interval);
                    window.location.href = '{{ route("student.submit", $mockTest->id) }}';
                }
            }, 1000);

            return;
        }

        countdownEl.innerText = formatTime(totalSeconds);
        totalSeconds--;
    }

    const countdownInterval = setInterval(updateCountdown, 1000);
    updateCountdown();

    // ✅ Save time every 10 seconds
    setInterval(() => {
        saveRemainingTime(totalSeconds);
    }, 10000);

    function saveRemainingTime(seconds) {
        fetch('{{ route("student.saveAnswer", $mockTest->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                save_only: true,
                remaining_seconds: seconds
            })
        });
    }
</script>

<!-- ✅ Answer Submit + Navigation -->
<script>
    const form = document.getElementById('questionForm');
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('remaining_seconds', totalSeconds);

        const statusDiv = document.getElementById('saveStatus');
        const isLast = formData.get('is_last_question') === '1';

        try {
            const response = await fetch('{{ route("student.saveAnswer", $mockTest->id) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData,
            });
            const result = await response.json();

            if (response.ok && result.success) {
                statusDiv.textContent = 'Answer saved successfully!';
                statusDiv.className = 'text-success';

                if (isLast) {
                    showFinalConfirmation();
                } else {
                    const nextQ = form.querySelector('[name="next_question_number"]').value;
                    window.location.href = '{{ route("student.test", [$mockTest->id, "__q__"]) }}'.replace('__q__', nextQ);
                }
            } else {
                statusDiv.textContent = result.message || 'Error while saving!';
                statusDiv.className = 'text-danger';
            }
        } catch (error) {
            statusDiv.textContent = 'An error occurred while submitting the answer.';
            statusDiv.className = 'text-danger';
        }
    });

    // ✅ Save time before navigating back
    const prevButton = document.querySelector('.save-time');
    if (prevButton) {
        prevButton.addEventListener('click', async function (e) {
            e.preventDefault();
            const link = this.getAttribute('href');

            try {
                await saveRemainingTime(totalSeconds);
                window.location.href = link;
            } catch (error) {
                window.location.href = link;
            }
        });
    }
</script>

<!-- ✅ Final Submit Modal -->
<script>
    async function showFinalConfirmation() {
        try {
            const countsResponse = await fetch('{{ route("student.getAnswerCounts", $mockTest->id) }}', {
                method: 'GET',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            });
            const counts = await countsResponse.json();

            document.getElementById('answeredCount').textContent = counts.answered ?? 0;
            document.getElementById('unansweredCount').textContent = counts.not_answered ?? 0;
            document.getElementById('answeredFlaggedCount').textContent = counts.answered_flagged ?? 0;
            document.getElementById('unansweredFlaggedCount').textContent = counts.unanswered_flagged ?? 0;

            const finalSubmitModal = new bootstrap.Modal(document.getElementById('finalSubmitModal'));
            finalSubmitModal.show();

            document.getElementById('confirmFinalSubmit').onclick = function () {
                window.location.href = '{{ route("student.submit", $mockTest->id) }}';
            };
        } catch (error) {
            alert('Error loading final confirmation status. Proceeding to submission.');
            window.location.href = '{{ route("student.submit", $mockTest->id) }}';
        }
    }
</script>

<!-- ✅ Question Palette Loader -->
<script>
    const statusContainer = document.getElementById('statusContainer');
    const currentQuestionNumber = {{ $questionNumber }};

    async function loadQuestionStatuses() {
        try {
            const response = await fetch('{{ route("student.getQuestionStatuses", $mockTest->id) }}');
            const statuses = await response.json();

            statusContainer.innerHTML = '';
            statuses.forEach(status => {
                let bgClass = 'bg-secondary';
                let title = `Q${status.index}: Not Answered`;
                let icon = '';

                if (status.is_answered) {
                    bgClass = 'bg-success';
                    title = `Q${status.index}: Answered`;
                }
                if (status.index == currentQuestionNumber) {
                    bgClass = 'bg-primary';
                    title = `Q${status.index}: Current Question`;
                }
                if (status.is_flagged) {
                    icon = '<i class="bi bi-flag-fill text-danger" title="Flagged"></i>';
                    title += ' (Flagged)';
                }

                statusContainer.innerHTML += `
                    <a 
                        href="{{ route('student.test', $mockTest->id) }}/${status.index}" 
                        class="badge rounded-pill ${bgClass} position-relative text-decoration-none"
                        title="${title}"
                    >
                        ${status.index}
                        ${icon}
                    </a>`;
            });
        } catch (error) {
            console.error(error);
        }
    }

    loadQuestionStatuses();
</script>

<!-- ✅ Flag Button Toggle -->
<script>
const flagButton = document.getElementById('flagButton');

flagButton.addEventListener('click', async function () {
    const questionId = '{{ $question->id }}';
    const icon = flagButton.querySelector('i');
    const text = flagButton.querySelector('.flag-text');

    try {
        const response = await fetch('{{ route("student.toggleFlag", $mockTest->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ question_id: questionId })
        });

        const result = await response.json();

        if (result.is_flagged) {
            icon.classList.remove('bi-flag');
            icon.classList.add('bi-flag-fill');
            flagButton.classList.add('flagged');
            flagButton.setAttribute('title', 'Unflag this question');
            flagButton.setAttribute('data-flagged', '1');
            text.textContent = 'Unflag';
        } else {
            icon.classList.remove('bi-flag-fill');
            icon.classList.add('bi-flag');
            flagButton.classList.remove('flagged');
            flagButton.setAttribute('title', 'Flag this question');
            flagButton.setAttribute('data-flagged', '0');
            text.textContent = 'Flag';
        }

        loadQuestionStatuses();
    } catch (error) {
        console.error('Error toggling flag:', error);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const icon = flagButton.querySelector('i');
    const text = flagButton.querySelector('.flag-text');

    if (flagButton.getAttribute('data-flagged') === '1') {
        icon.classList.remove('bi-flag');
        icon.classList.add('bi-flag-fill');
        flagButton.classList.add('flagged');
        text.textContent = 'Unflag';
    } else {
        icon.classList.remove('bi-flag-fill');
        icon.classList.add('bi-flag');
        flagButton.classList.remove('flagged');
        text.textContent = 'Flag';
    }
});

</script>


   




</body>
</html>

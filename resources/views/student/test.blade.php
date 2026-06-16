<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ACCAPrep with Malasri</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

        <style>
        :root {
            --primary: #b46e4c;
            --primary-dark: #832b00;
            --primary-light: #f7e3d8;

            --success: #198754;
            --danger: #dc3545;
            --secondary: #6c757d;

            --surface: #ffffff;
            --border: #e5e7eb;
        }

        .btn-progress {
                border-color: var(--primary);
                color: var(--primary-dark);
                background-color: #fff;
            }

            .btn-progress:hover {
                background-color: var(--primary-light);
                border-color: var(--primary);
                color: var(--primary-dark);
            }

             .btn-nav-action:focus,
            .btn-nav-action:active,
            .btn-nav-action.show,
            .show > .btn-nav-action.dropdown-toggle {
                background: var(--primary-light) !important;
                border-color: var(--primary) !important;
                color: var(--primary-dark) !important;
                box-shadow: none !important;
            }

        body {
            background-color: #f7f8fa;
            background-image:
                radial-gradient(#e9ecef 1px, transparent 1px);
            background-size: 24px 24px;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ---------- NAVBAR ---------- */

        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            z-index: 1000;
        }

        .student-logo {
            height: 50px;
            width: auto;
            display: block;
        }

        .timer-display {
            color: var(--primary-dark);
            font-weight: 600;
        }

        .test-name-label {
            font-weight: 500;
            font-size: 0.95rem;
            color: #6c757d;
            margin: 0 12px;
            text-transform: uppercase;
        }

        /* Radio Buttons */
            input[type="radio"] {
                accent-color: var(--primary);
            }

            /* Checkboxes (Multiple Select) */
            input[type="checkbox"] {
                accent-color: var(--primary);
            }

            .small-radio {
                transform: scale(1.1);
                accent-color: var(--primary);
                cursor: pointer;
            }

        /* ---------- BUTTONS ---------- */

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-primary:focus,
        .btn-primary:active {
            background-color: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
            box-shadow: none !important;
        }

        .btn-progress {
            border-color: var(--primary);
            color: var(--primary-dark);
        }

        .btn-progress:hover {
            background-color: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary-dark);
        }

        .help-button {
            border-color: #e5d2c8;
            color: var(--primary-dark);
        }

        .help-button:hover {
            background-color: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary-dark);
        }

        .exit-button {
            border: 1px solid #e5d2c8;
            background-color: #ffffff;
            color: var(--primary-dark);
            font-size: 0.9rem;
            padding: 6px 14px;
            border-radius: 20px;
            transition: all 0.2s ease-in-out;
        }

        .exit-button:hover {
            background-color: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary-dark);
        }

        .exit-button:focus,
        .exit-button:active {
            background-color: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary-dark);
            box-shadow: none;
        }

        /* ---------- TEST CONTAINER ---------- */

        .test-container {
            max-width: 900px;
            margin: 100px auto 40px;
            background: #fff;
            border-top: 4px solid var(--primary);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,.05);
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

        /* ---------- OPTIONS ---------- */

        .option {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .option:hover {
            background-color: var(--primary-light);
        }

        .selected-option {
            border-color: var(--primary);
            background-color: var(--primary-light);
        }

        .option input[type="radio"],
        .option input[type="checkbox"] {
            margin-right: 10px;
        }

        /* ---------- TOP BAR ---------- */

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .question-count {
            color: #6b7280;
            font-weight: 500;
        }

        /* ---------- FLAG ---------- */

        .flag-btn {
            border: none;
            background: none;
            color: #9c3d16;
            cursor: pointer;
        }

        .flagged {
            color: #dc3545 !important;
        }

        /* ---------- TABLE MCQ ---------- */

        .table-mcq-table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-mcq-table th,
        .table-mcq-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: center;
        }

        .table-mcq-container {
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .table-mcq-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-mcq-table .sticky-col {
            position: sticky;
            left: 0;
            background: #fff;
            z-index: 2;
            min-width: 140px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .small-radio {
            transform: scale(0.8);
            margin: 0;
        }

        /* ---------- PROGRESS DROPDOWN ---------- */

        #progressDropdownMenu.dropdown-menu {
            min-width: 300px !important;
            max-width: 90vw;
        }

        .dropdown {
            position: relative;
        }

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

        #statusContainer .col {
            padding-left: 4px;
            padding-right: 4px;
        }

        #statusContainer button {
            padding: 0.4rem 0.6rem;
            border-radius: 999px;
            font-size: 0.75rem;
            border: none;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .bg-current {
            background-color: var(--primary) !important;
        }

        /* ---------- MOBILE ---------- */

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

            .student-logo {
                height: 42px;
            }

            .exit-button {
                width: 38px;
                height: 38px;
                padding: 0;
                border-radius: 50%;
                justify-content: center;
            }
        }

        .draggable-item {
                background: var(--primary);
            }

            .draggable-item:hover {
                background: var(--primary-dark);
            }


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

        .option {
                cursor: pointer;
            }

            .option label,
            .option span {
                cursor: pointer;
            }

            .btn-nav-action {
                border-color: #e5d2c8;
                color: var(--primary-dark);
                background: #fff;
            }

            .btn-nav-action:hover {
                background: var(--primary-light);
                border-color: var(--primary);
                color: var(--primary-dark);
            }

            .test-name-label {
                background: var(--primary-light);
                color: var(--primary-dark);
                padding: 6px 12px;
                border-radius: 999px;
                font-size: .85rem;
                font-weight: 600;
                margin: 0;
            }

            .exit-button {
                background: transparent;
                border: none;
                color: #6c757d;
                padding: 6px 10px;
                border-radius: 8px;
                transition: all .2s ease;
            }

            .exit-button:hover {
                background: var(--primary-light);
                color: var(--primary-dark);
            }

            .exit-button:focus,
            .exit-button:active {
                background: var(--primary-light);
                color: var(--primary-dark);
                box-shadow: none;
            }

            .form-control:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 0.15rem rgba(180, 110, 76, 0.15);
            }
            
            .form-select:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 0.15rem rgba(180, 110, 76, 0.15);
            }
        </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom fixed-top py-2">
    <div class="container-fluid px-3">
        <div class="d-flex justify-content-between align-items-center w-100 flex-wrap">
            <!-- Brand -->
            <a class="navbar-brand brand-logo d-flex flex-column align-items-start text-decoration-none" href="#">
                <img src="{{ asset('images/Logo.png') }}"
                    alt="ACCAPrep with Malasri"
                    class="student-logo">
            </a>

            <!-- Timer + Progress + Help + Exit -->
            <div class="d-flex flex-wrap justify-content-end align-items-center gap-2 mt-2 mt-lg-0" style="flex: 1 1 auto;">
                <!-- Timer -->
                <div class="d-flex align-items-center  fw-semibold timer-display">
                    <i class="bi bi-clock me-1"></i>
                    <span id="countdown">Loading...</span>
                </div>

               <!-- Progress Dropdown -->
               <div class="dropdown">
                <button class="btn btn-nav-action btn-sm dropdown-toggle" type="button" id="progressDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bar-chart-fill me-1"></i> 
                    <span class="progress-label">Progress </span>
                </button>
                
                <ul id="progressDropdownMenu" class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="progressDropdown">
                    <li class="legend-row text-muted">
                        <span><span class="badge rounded-pill bg-success">&nbsp;</span> Answered</span>
                        <span><span class="badge rounded-pill bg-secondary">&nbsp;</span> Not Answered</span>
                        <span><span class="badge rounded-pill bg-current">&nbsp;</span> Current</span>
                        <span><i class="bi bi-flag-fill text-danger"></i> Flagged</span>
                    </li>
                    <hr class="my-2">
                    <li>
                        <div id="statusContainer" class="d-grid gap-2" style="grid-template-columns: repeat(auto-fit, minmax(36px, 1fr)); max-height: 250px; overflow-y: auto;">
                            <!-- Status buttons go inside here -->
                        </div>
                    </li>
                </ul>

            </div>




                <!-- Help Button -->
                <button class="btn btn-nav-action btn-sm" data-bs-toggle="modal" data-bs-target="#helpModal">
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
                <form id="exitForm" action="{{ route('student.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="exit-button btn btn-sm d-flex align-items-center">
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
                        <label class="option w-100">
                            <input
                                type="checkbox"
                                name="answer[]"
                                value="{{ $key }}"
                                id="chk{{ $key }}"
                                @if(is_array($selectedOption) && in_array($key, $selectedOption)) checked @endif
                            >

                            <span>{{ $text }}</span>
                        </label>
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
                    <div class="table-mcq-container">
                        <div class="table-mcq-scroll">
                            <table class="table table-bordered table-mcq-table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="sticky-col">Statement</th>
                                        @foreach($labels as $label)
                                            <th class="text-center">{{ ucfirst($label) }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($statements as $index => $statement)
                                        <tr>
                                            <td class="sticky-col">{{ $statement }}</td>
                                            @foreach($labels as $label)
                                                <td class="text-center">
                                                    <input
                                                        type="radio"
                                                        name="answer[{{ $index }}]"
                                                        value="{{ strtolower($label) }}"
                                                        class="small-radio"
                                                        @if(isset($selectedOption[$index]) && $selectedOption[$index] === strtolower($label)) checked @endif
                                                    >
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                   {{-- DRAG AND DROP Question --}}
                    @elseif($question->question_type === 'drag_and_drop')
                    @php
                        $colA = $question->options['column_a'] ?? [];
                        $colB = $question->options['column_b'] ?? [];
                        $aLabel = $question->options['column_a_label'] ?? 'Column A';
                        $bLabel = $question->options['column_b_label'] ?? 'Column B';

                        // Previously selected option: [aIndex => bIndex]
                        $selectedMap = is_array($selectedOption)
                        ? $selectedOption
                        : [];

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
                                                        <div class="draggable draggable-item text-white rounded px-3 py-2"
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

                  @php
                    $availableOptions = [];

                    foreach ($colA as $index => $item) {
                        if (!in_array($index, $bToA)) {
                            $availableOptions[] = [
                                'index' => $index,
                                'text' => $item,
                            ];
                        }
                    }

                    shuffle($availableOptions);
                @endphp

                <div class="mt-4">
                    <h6 class="mb-2">Draggable Options ({{ $aLabel }})</h6>

                    <div class="d-flex flex-wrap gap-2 border rounded p-3 bg-light"
                        id="drag-options"
                        ondrop="dropToBank(event)"
                        ondragover="allowDrop(event)">

                        @foreach($availableOptions as $option)

                            <div class="draggable draggable-item text-white rounded px-3 py-2"
                                draggable="true"
                                ondragstart="drag(event)"
                                id="drag-{{ $option['index'] }}"
                                data-value="{{ $option['index'] }}">

                                {{ $option['text'] }}

                            </div>

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

                            const draggedId =
                                ev.dataTransfer.getData("text");

                            const draggedEl =
                                document.getElementById(draggedId);

                            const dropZone =
                                document.getElementById("drop-" + dropIndex);

                            const inputField =
                                document.getElementById("drop-input-" + dropIndex);

                            const dragBank =
                                document.getElementById("drag-options");

                            // If target already contains an item,
                            // move it back to options bank

                            const existingItem =
                                dropZone.querySelector('.draggable');

                            if (existingItem && existingItem !== draggedEl) {

                                dragBank.appendChild(existingItem);

                                const zoneIndex =
                                    dropZone.id.split('-')[1];

                                document.getElementById(
                                    'drop-input-' + zoneIndex
                                ).value = '';
                            }

                            // Remove dragged item from previous dropzone

                            document.querySelectorAll('.dropzone').forEach(zone => {

                                if (zone.contains(draggedEl)) {

                                    const zoneIndex =
                                        zone.id.split('-')[1];

                                    zone.innerHTML = 'Drop here';

                                    document.getElementById(
                                        "drop-input-" + zoneIndex
                                    ).value = '';
                                }
                            });

                            // Place item in new dropzone

                            dropZone.innerHTML = '';

                            dropZone.appendChild(draggedEl);

                            inputField.value =
                                draggedEl.dataset.value;
                        }

                        function dropToBank(ev) {

                            ev.preventDefault();

                            const draggedId =
                                ev.dataTransfer.getData("text");

                            const draggedEl =
                                document.getElementById(draggedId);

                            const dragBank =
                                document.getElementById("drag-options");

                            // Clear hidden answer if item came from dropzone

                            document.querySelectorAll('.dropzone').forEach(zone => {

                                if(zone.contains(draggedEl)) {

                                    const zoneIndex =
                                        zone.id.split('-')[1];

                                    zone.innerHTML = 'Drop here';

                                    document.getElementById(
                                        'drop-input-' + zoneIndex
                                    ).value = '';
                                }

                            });

                            dragBank.appendChild(draggedEl);
                        }
                    </script>


                    @elseif($question->question_type == 'dropdown')
                    @php
                        $sentence = $question->question_text; // e.g., "Captain of [blank] is MS Dhoni in [blank]."
                        $dropdowns = is_array($question->options) ? $question->options : json_decode($question->options, true);
                        $selected = is_array($selectedOption)
                        ? $selectedOption
                        : json_decode($selectedOption, true);
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
                        <button type="submit" class="btn btn-primary btn-nav">
                            Review & Submit
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
                <div class="modal-content border-0 shadow" style="max-height: 80vh; border-radius: 1rem; overflow: hidden;">

                    <div class="modal-header">
                        <h5 class="modal-title fw-semibold" id="helpModalLabel">
                            <i class="bi bi-question-circle me-2" style="color: var(--primary-dark);"></i>
                            Help & Instructions
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body overflow-auto">

                        <div class="mb-4">
                            <h6 class="fw-semibold">
                                <i class="bi bi-clock me-2" style="color: var(--primary);"></i>
                                Timer
                            </h6>
                            <p class="mb-0">
                                Your remaining time is shown at the top
                                (<strong style="color: var(--primary-dark);">MM:SS</strong>).
                                The test will be automatically submitted when the timer reaches zero.
                            </p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-semibold">
                                <i class="bi bi-box-arrow-right me-2" style="color: var(--primary);"></i>
                                Navigation
                            </h6>
                            <ul class="mb-0">
                                <li>
                                    <strong>Previous</strong> – Move to the previous question without saving new changes.
                                </li>
                                <li>
                                    <strong>Save & Next</strong> – Save your answer and move to the next question.
                                </li>
                                <li>
                                    Only answers saved using <strong>Save & Next</strong> are considered for evaluation.
                                </li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-semibold">
                                <i class="bi bi-flag me-2 text-danger"></i>
                                Flag Questions
                            </h6>
                            <p class="mb-0">
                                Use the flag button to mark a question for review.
                                Flagged questions remain highlighted in the Progress panel until reviewed or unflagged.
                            </p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-semibold">
                                <i class="bi bi-bar-chart-fill me-2" style="color: var(--primary);"></i>
                                Progress Panel
                            </h6>

                            <p>The Progress dropdown shows the status of every question.</p>

                            <ul class="mb-0">
                                <li><span class="badge bg-success">●</span> Answered</li>
                                <li><span class="badge bg-secondary">●</span> Not Answered</li>
                                <li>
                                    <span class="badge"
                                        style="background: var(--primary); color: white;">
                                        ●
                                    </span>
                                    Current Question
                                </li>
                                <li><i class="bi bi-flag-fill text-danger"></i> Flagged for Review</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-semibold">
                                <i class="bi bi-check2-square me-2 text-success"></i>
                                Final Submission
                            </h6>
                            <p class="mb-0">
                                Before submitting, you will see a summary showing answered,
                                unanswered, and flagged questions.
                                Once submitted, answers cannot be changed.
                            </p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-semibold">
                                <i class="bi bi-journal-text me-2" style="color: var(--primary);"></i>
                                Supported Question Types
                            </h6>

                            <ul class="mb-0">
                                <li><strong>MCQ</strong> – Select one correct option.</li>
                                <li><strong>Multiple Select</strong> – Select all correct options.</li>
                                <li><strong>One Word</strong> – Enter a word, number, or short answer.</li>
                                <li><strong>Table MCQ</strong> – Choose the correct option for each row.</li>
                                <li><strong>Drag & Drop</strong> – Match items by dragging them into the correct positions.</li>
                                <li><strong>Dropdown</strong> – Complete the blanks using dropdown selections.</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-semibold">
                                <i class="bi bi-info-circle me-2 text-secondary"></i>
                                Test Overview
                            </h6>

                            <ul class="mb-0">
                                <li><strong>Total Questions:</strong> {{ $mockTest->questions->count() }}</li>
                                <li><strong>Total Marks:</strong> {{ $mockTest->questions->sum('marks') }}</li>
                                <li><strong>Duration:</strong> {{ $mockTest->duration_minutes }} minutes</li>
                            </ul>
                        </div>

                        <div class="alert border-0 mb-0"
                            style="background: var(--primary-light); color: var(--primary-dark);">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <strong>Important:</strong>
                            Answers are saved only when you click
                            <strong>Save & Next</strong>.
                        </div>

                    </div>
                </div>
            </div>
        </div>



        <!-- Final Submission Modal -->
        <div class="modal fade" id="finalSubmitModal" tabindex="-1" aria-labelledby="finalSubmitModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow" style="border-radius: 1rem; overflow: hidden;">

                    <div class="modal-header" style="background: var(--primary-light);">
                        <h5 class="modal-title fw-semibold" id="finalSubmitModalLabel">
                            <i class="bi bi-check-circle me-2" style="color: var(--primary-dark);"></i>
                            Final Submission
                        </h5>
                    </div>

                    <div class="modal-body">

                        <p class="fw-semibold mb-3">
                            Review your test status before submitting:
                        </p>

                        <div class="d-grid gap-2">

                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    Answered
                                </span>
                                <span class="badge bg-success rounded-pill" id="answeredCount">
                                    0
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center ps-4">
                                <span class="text-muted">
                                    <i class="bi bi-flag-fill text-warning me-1"></i>
                                    Flagged
                                </span>
                                <span class="badge bg-warning text-dark rounded-pill" id="answeredFlaggedCount">
                                    0
                                </span>
                            </div>

                            <hr class="my-2">

                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="bi bi-dash-circle-fill text-secondary me-1"></i>
                                    Not Answered
                                </span>
                                <span class="badge bg-secondary rounded-pill" id="unansweredCount">
                                    0
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center ps-4">
                                <span class="text-muted">
                                    <i class="bi bi-flag-fill text-danger me-1"></i>
                                    Flagged
                                </span>
                                <span class="badge bg-danger rounded-pill" id="unansweredFlaggedCount">
                                    0
                                </span>
                            </div>

                        </div>

                        <div class="alert border-0 mt-4 mb-0"
                            style="background: var(--primary-light); color: var(--primary-dark);">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            Once submitted, your answers cannot be modified.
                        </div>

                    </div>

                    <div class="modal-footer border-0">

                        <button id="cancelFinalSubmit"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">
                            Review Again
                        </button>

                        <button id="confirmFinalSubmit"
                                class="btn btn-primary">
                            <i class="bi bi-check2-circle me-1"></i>
                            Submit Test
                        </button>

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
                    bgClass = 'bg-current';
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

    document.getElementById('exitForm').addEventListener('submit', function(event) {
        const confirmExit = confirm("Are you sure you want to exit the test?\nYour progress will be saved.");
        if (!confirmExit) {
            event.preventDefault(); // Stop form from submitting
        }
    });

  
        document.addEventListener('DOMContentLoaded', function () {

            const oneWordInput =
                document.getElementById('oneWordAnswer');

            if (!oneWordInput) return;

            // Prevent paste
            oneWordInput.addEventListener('paste', function(e){
                e.preventDefault();
            });

            // Prevent drag-drop text
            oneWordInput.addEventListener('drop', function(e){
                e.preventDefault();
            });

            // Prevent right-click menu
            oneWordInput.addEventListener('contextmenu', function(e){
                e.preventDefault();
            });

            oneWordInput.addEventListener('paste', function(e){
                e.preventDefault();
                alert('Paste is not allowed for One Word questions.');
            });

        });


</script>


   




</body>
</html>

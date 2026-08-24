      
      <div class="question-header">
                    @if(!in_array($item->question_type, ['dropdown']))
                        <div class="question-text">{!! $item->question_text !!}</div>
                    @endif

                </div>

                <form id="questionForm">
                    @csrf
                    <input type="hidden" name="question_id" value="{{ $item->id }}">
                    <input type="hidden" name="next_question_number" value="{{ $questionNumber + 1 }}">
                    <input type="hidden" name="is_last_question" value="{{ $questionNumber == $totalQuestions ? '1' : '0' }}">

                    {{-- MCQ --}}
                    @if($item->question_type == 'mcq')
                        @php
                            $options = is_array($item->options) ? $item->options : json_decode($item->options, true);
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
                    @elseif($item->question_type == 'multiple_select')
                        @php
                            $options = is_array($item->options) ? $item->options : json_decode($item->options, true);
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
                    @elseif($item->question_type == 'one_word')
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
                    @elseif($item->question_type === 'table_mcq' && is_array($statements) && is_array($labels))
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
                        @elseif($item->question_type === 'drag_and_drop')
                        @php
                            $colA = $item->options['column_a'] ?? [];
                            $colB = $item->options['column_b'] ?? [];
                            $aLabel = $item->options['column_a_label'] ?? 'Column A';
                            $bLabel = $item->options['column_b_label'] ?? 'Column B';

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


                        @elseif($item->question_type == 'dropdown')
                        @php
                            $sentence = $item->question_text; // e.g., "Captain of [blank] is MS Dhoni in [blank]."
                            $dropdowns = is_array($item->options) ? $item->options : json_decode($item->options, true);
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
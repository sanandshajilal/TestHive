<div id="drag-drop-section" class="mb-3 {{ old('question_type', $question->question_type ?? '') !== 'drag_and_drop' ? 'd-none' : '' }}">
    <div class="row mb-3">
        @if ($errors->any())
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="col-md-6">
            <label class="form-label">Column A Label</label>
            <input type="text" name="column_a_label" class="form-control" placeholder="e.g. Statements"
                   value="{{ old('column_a_label', $question->column_a_label ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Column B Label</label>
            <input type="text" name="column_b_label" class="form-control" placeholder="e.g. Budgeting Approach"
                   value="{{ old('column_b_label', $question->column_b_label ?? '') }}">
        </div>
    </div>

    <hr>

    <label class="form-label">Column A Items (Draggable)</label>
    <div id="column-a-list">
        @php
            $columnA = old('column_a', $question->column_a ?? []);
        @endphp

        @if (!empty($columnA))
            @foreach ($columnA as $item)
                <div class="input-group mb-2 column-a-item">
                    <input type="text" name="column_a[]" class="form-control" placeholder="Enter Column A Item" value="{{ $item }}">
                    <button type="button" class="btn btn-outline-danger remove-a-item">X</button>
                </div>
            @endforeach
        @else
            <div class="input-group mb-2 column-a-item">
                <input type="text" name="column_a[]" class="form-control" placeholder="Enter Column A Item">
                <button type="button" class="btn btn-outline-danger remove-a-item">X</button>
            </div>
        @endif
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary" id="addColumnA">+ Add Column A</button>

    <hr class="my-4">

    <label class="form-label">Column B Items (Drop Zones)</label>
    <div id="column-b-list">
        @php
            $columnB = old('column_b', $question->column_b ?? []);
        @endphp

        @if (!empty($columnB))
            @foreach ($columnB as $item)
                <div class="input-group mb-2 column-b-item">
                    <input type="text" name="column_b[]" class="form-control" placeholder="Enter Column B Item" value="{{ $item }}">
                    <button type="button" class="btn btn-outline-danger remove-b-item">X</button>
                </div>
            @endforeach
        @else
            <div class="input-group mb-2 column-b-item">
                <input type="text" name="column_b[]" class="form-control" placeholder="Enter Column B Item">
                <button type="button" class="btn btn-outline-danger remove-b-item">X</button>
            </div>
        @endif
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary" id="addColumnB">+ Add Column B</button>

    <hr class="my-4">

    <label class="form-label">Correct Matching (Column A ➝ Column B by Index)</label>
    <div id="match-list">
        @php
            $matchingFrom = old('matching_from', $question->matching_from ?? []);
            $matchingTo = old('matching_to', $question->matching_to ?? []);
        @endphp

        @if (!empty($matchingFrom))
            @foreach ($matchingFrom as $i => $from)
                <div class="row g-2 align-items-center match-pair mb-2">
                    <div class="col-5">
                        <input type="number" name="matching_from[]" class="form-control"
                               value="{{ $from }}" placeholder="A Index (e.g. 0)">
                    </div>
                    <div class="col-5">
                        <input type="number" name="matching_to[]" class="form-control"
                               value="{{ $matchingTo[$i] ?? '' }}" placeholder="B Index (e.g. 2)">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-outline-danger remove-match">X</button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row g-2 align-items-center match-pair mb-2">
                <div class="col-5">
                    <input type="number" name="matching_from[]" class="form-control" placeholder="A Index (e.g. 0)">
                </div>
                <div class="col-5">
                    <input type="number" name="matching_to[]" class="form-control" placeholder="B Index (e.g. 2)">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-outline-danger remove-match">X</button>
                </div>
            </div>
        @endif
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary" id="addMatch">+ Add Match</button>
</div>

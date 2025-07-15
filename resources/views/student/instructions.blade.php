@extends('layouts.student')

@section('content')

<style>
    body {
        background-color: #f8f9fc;
        font-family: 'Poppins', sans-serif;
    }

    .instruction-wrapper {
        max-width: 880px;
        margin: 40px auto;
        background: white;
        border-radius: 12px;
        padding: 30px 40px;
        box-shadow: 0 0 12px rgba(0,0,0,0.06);
        border-top: 6px solid #4e73df;
    }

    .brand-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 12px;
    }

    .brand-header h4 {
        color: #4e73df;
        font-weight: 700;
        margin: 0;
    }

    .divider {
        border-top: 1px solid #e4e6eb;
        margin: 10px 0 20px;
    }

    .step-indicator {
        text-align: center;
        font-weight: 500;
        color: #6c757d;
        background: #f1f3f7;
        padding: 8px 0;
        border-radius: 6px;
        margin-bottom: 25px;
    }

    .slide {
        display: none;
    }

    .slide.active {
        display: block;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #4e73df;
        border-left: 4px solid #4e73df;
        padding-left: 12px;
        margin-bottom: 18px;
    }

    ul {
        padding-left: 18px;
    }

    ul li {
        margin-bottom: 10px;
        line-height: 1.6;
    }

    .btn-group-nav {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }

    .btn-nav {
        width: 130px;
    }

    .btn-start {
        width: 100%;
    }

    .icon {
        display: inline-block;
        margin-right: 6px;
    }
</style>

<div class="instruction-wrapper">
    <div class="brand-header">
        <h4 class="text-uppercase">{{ $mockTest->title }}</h4>
        <div><small class="text-muted">Practice Test</small></div>
    </div>

    <div class="divider"></div>

    <div class="step-indicator">
        Instructions <span id="step-num">1</span> of 4
    </div>

    {{-- Slide 1: Overview --}}
    <div class="slide active" id="slide-1">
        <div class="section-title">Test Overview</div>
        <ul>
            <li><strong>Total Questions:</strong> {{ $mockTest->questions_count }}</li>
            <li><strong>Total Marks:</strong> {{ $mockTest->questions->sum('marks') }}</li>
            <li><strong>Time Allowed:</strong> {{ $mockTest->duration }} minutes</li>
            <li><strong>Start:</strong> Timer begins when you click <em>Start Test</em></li>
            <li><strong>Scoring:</strong> Each question carries 2 marks. No negative marking.</li>
        </ul>
    </div>

    {{-- Slide 2: Navigation --}}
    <div class="slide" id="slide-2">
        <div class="section-title">Navigation & Controls</div>
        <ul>
            <li>Use <strong>Previous</strong> and <strong>Save & Next</strong> buttons to navigate questions.</li>
            <li><strong>Save & Next</strong> saves your answer and goes to the next question.</li>
            <li>Click <i class="bi bi-flag-fill text-danger"></i> to flag a question. Click again to unflag.</li>
            <li>
                Use the 
                <span style="color: #0d6efd; font-weight: 500;">
                    <i class="bi bi-bar-chart-fill me-1"></i>Progress
                </span> 
                dropdown to jump to any question.
            </li>

        </ul>
    </div>

    {{-- Slide 3: Status Indicators --}}
    <div class="slide" id="slide-3">
        <div class="section-title">Answer Status Indicators</div>
        <ul>
            <li><span class="badge bg-success">●</span> Answered</li>
            <li><span class="badge bg-secondary">●</span> Not Answered</li>
            <li><span class="badge bg-primary">●</span> Current Question</li>
            <li><i class="bi bi-flag-fill text-danger"></i> Flagged for Review</li>
            <li><strong>Note:</strong> Unanswered or flagged questions will be shown in the summary before submission.</li>
        </ul>
    </div>

    {{-- Slide 4: Final Submission & Rules --}}
    <div class="slide" id="slide-4">
        <div class="section-title">Submission & Rules</div>
        <ul>
            <li>Test auto-submits when timer ends.</li>
            <li>You can submit manually at any time via the <strong>Submit</strong> button after the last question.</li>
            <li>You will be warned if questions are left unanswered or flagged before submission.</li>
            <li>Do not refresh or close the browser during the test.</li>
        </ul>
        <form method="POST" action="{{ route('student.startTest') }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-start mt-4">Start Test</button>
        </form>
    </div>

    <div class="btn-group-nav">
        <button id="prevBtn" class="btn btn-outline-secondary btn-nav" disabled>Previous</button>
        <button id="nextBtn" class="btn btn-primary btn-nav">Next</button>
    </div>
</div>

<script>
    let current = 1;
    const total = 4;

    const showSlide = (n) => {
        document.querySelectorAll('.slide').forEach((el, i) => {
            el.classList.toggle('active', i === n - 1);
        });

        document.getElementById('step-num').textContent = n;
        document.getElementById('prevBtn').disabled = (n === 1);
        document.getElementById('nextBtn').style.display = (n === total) ? 'none' : 'inline-block';
    };

    document.getElementById('nextBtn').onclick = () => {
        if (current < total) current++;
        showSlide(current);
    };

    document.getElementById('prevBtn').onclick = () => {
        if (current > 1) current--;
        showSlide(current);
    };
</script>

@endsection
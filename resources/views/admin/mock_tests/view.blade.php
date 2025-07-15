@extends('layouts.app')

@section('title', 'Mock Test Preview')

@section('content')
<div class="container">
    <h3 class="mb-4">Mock Test: {{ $mockTest->title }}</h3>

    <div class="mb-3">
        <strong>Paper:</strong> {{ $mockTest->paper->name ?? 'N/A' }}<br>
        <strong>Scheduled At:</strong> {{ \Carbon\Carbon::parse($mockTest->scheduled_at)->format('d M Y, h:i A') }}<br>
        <strong>Duration:</strong> {{ $mockTest->duration_minutes }} minutes
    </div>

    @if($questions->count())
        @foreach($questions as $index => $question)
            <div class="mb-4 p-3 border rounded">
                <strong>Q{{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}.</strong>
                {!! nl2br(e($question->question_text)) !!}

                @if($question->question_type === 'mcq')
                    <ul class="mt-2">
                        @if($question->option_a) <li><strong>A:</strong> {{ $question->option_a }}</li> @endif
                        @if($question->option_b) <li><strong>B:</strong> {{ $question->option_b }}</li> @endif
                        @if($question->option_c) <li><strong>C:</strong> {{ $question->option_c }}</li> @endif
                        @if($question->option_d) <li><strong>D:</strong> {{ $question->option_d }}</li> @endif
                    </ul>
                @endif


                <div><strong>Difficulty:</strong> {{ ucfirst($question->difficulty) }}</div>
                <div><strong>Type:</strong> {{ strtoupper($question->question_type) }}</div>
            </div>
        @endforeach

        <div class="d-flex justify-content-center">
            {{ $questions->links() }}
        </div>
    @else
        <p>No questions assigned to this mock test.</p>
    @endif

    @if($mockTest->scheduled_at > now())
        <a href="{{ route('mock-tests.edit', $mockTest->id) }}" class="btn btn-primary mt-4">Edit Mock Test</a>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('styles')
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        background-color: #fff;
        padding: 1.5rem;
    }

    .accordion-button:not(.collapsed) {
        background-color: #e7f1ff;
        color: #0d6efd;
    }

    .accordion-button {
        font-weight: 500;
    }

    .accordion-body ul {
        padding-left: 1.25rem;
    }

    .accordion-body li {
        margin-bottom: 0.5rem;
    }

    .alert {
        margin-top: 1rem;
        border-radius: 0.5rem;
    }

    .btn-secondary {
        border-radius: 50px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="header-box mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Topics for Paper: <strong>{{ $paper->name }}</strong></h5>
        <a href="{{ route('papers.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Papers
        </a>
    </div>

    <div class="card-style">
        @if($topics->isEmpty())
            <div class="alert alert-info">No topics added yet for this paper.</div>
        @else
            <div class="accordion" id="topicsAccordion">
                @foreach($topics as $index => $topic)
                    <div class="accordion-item mb-3 border rounded">
                        <h2 class="accordion-header" id="heading-{{ $index }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $index }}">
                                {{ $topic->name }}
                            </button>
                        </h2>
                        <div id="collapse-{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#topicsAccordion">
                            <div class="accordion-body">
                                @if($topic->subTopics->isEmpty())
                                    <em>No subtopics under this topic.</em>
                                @else
                                    <ul>
                                        @foreach($topic->subTopics as $subTopic)
                                            <li>{{ $subTopic->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

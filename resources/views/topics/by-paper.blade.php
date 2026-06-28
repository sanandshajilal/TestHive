@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title','Paper Topics')

@section('styles')
<style>

body{
    background:#f9fafb;
}

/* =====================================
   HEADER
===================================== */

.header-box{

    position:relative;

    background:#fff;

    border-radius:1rem;

    padding:1.25rem 1.5rem;

    overflow:hidden;

    box-shadow:0 2px 10px rgba(180,110,76,.08);

}

.header-box::before{

    content:"";

    position:absolute;

    top:0;

    left:0;

    width:100%;

    height:5px;

    background:#832b00;

}

/* =====================================
   MAIN CARD
===================================== */

.card-style{

    background:#fff;

    border-radius:1rem;

    padding:1.75rem;

    box-shadow:0 2px 10px rgba(180,110,76,.08);

}

/* =====================================
   ACCORDION
===================================== */

.accordion-item{

    border:1px solid #edd7ca !important;

    border-radius:14px !important;

    overflow:hidden;

    margin-bottom:16px;

}

.accordion-button{

    background:#fff;

    color:#1f2937;

    font-weight:600;

    padding:1rem 1.25rem;

    box-shadow:none !important;

}

.accordion-button:not(.collapsed){

    background:#fcf7f3;

    color:#832b00;

}

.accordion-button::after{

    filter:sepia(100%) saturate(300%) hue-rotate(-10deg);

}

.accordion-body{

    background:#fff;

    padding:1.25rem;

}

/* =====================================
   TOPIC
===================================== */

.topic-title{

    display:flex;

    align-items:center;

    gap:.75rem;

}

.topic-icon{

    width:36px;

    height:36px;

    border-radius:50%;

    display:flex;

    align-items:center;

    justify-content:center;

    background:#f7e3d8;

    color:#832b00;

}

/* =====================================
   SUBTOPICS
===================================== */

.subtopic-list{

    list-style:none;

    margin:0;

    padding:0;

}

.subtopic-item{

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:.8rem 1rem;

    border:1px solid #edd7ca;

    border-radius:10px;

    margin-bottom:.75rem;

    transition:.2s;

}

.subtopic-item:hover{

    background:#fcf7f3;

    border-color:#d9b49c;

}

.subtopic-name{

    display:flex;

    align-items:center;

    gap:.75rem;

    color:#374151;

    font-weight:500;

}

.subtopic-icon{

    color:#b46e4c;

}

/* =====================================
   EMPTY STATE
===================================== */

.empty-box{

    text-align:center;

    padding:3rem;

    border:2px dashed #edd7ca;

    border-radius:16px;

    background:#fcf7f3;

}

.empty-box i{

    font-size:3rem;

    color:#b46e4c;

}

/* =====================================
   BUTTON
===================================== */

.btn-secondary{

    background:#f7e3d8;

    color:#832b00;

    border-color:#edd7ca;

    border-radius:50px;

}

.btn-secondary:hover{

    background:#b46e4c;

    color:#fff;

    border-color:#b46e4c;

}

/* =====================================
   MOBILE
===================================== */

@media(max-width:768px){

.header-box{

flex-direction:column;

align-items:flex-start !important;

gap:1rem;

}

.header-box .btn{

width:100%;

}

}

</style>
@endsection


@section('content')

<div class="container py-4">

<div class="header-box mb-4 d-flex justify-content-between align-items-center">

<div>

<h4 class="fw-semibold mb-0">

<i class="bi bi-journal-bookmark-fill me-2" style="color:#832b00;"></i>

{{ $paper->name }}

</h4>

<small class="text-muted">

Browse all topics and subtopics available under this paper.

</small>

</div>

<a href="{{ route('papers.index') }}" class="btn btn-secondary">

<i class="bi bi-arrow-left me-1"></i>

Back to Papers

</a>

</div>

<div class="card-style">

@if($topics->isEmpty())

    <div class="empty-box">

        <i class="bi bi-journal-x"></i>

        <h5 class="mt-3 mb-2 fw-semibold">
            No Topics Found
        </h5>

        <p class="text-muted mb-0">
            This paper does not have any topics yet.
        </p>

    </div>

@else

<div class="accordion" id="topicsAccordion">

@foreach($topics as $index => $topic)

<div class="accordion-item">

    <h2 class="accordion-header" id="heading{{ $index }}">

        <button
            class="accordion-button collapsed"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse{{ $index }}"
            aria-expanded="false">

            <div class="topic-title">

                <div class="topic-icon">

                    <i class="bi bi-folder2-open"></i>

                </div>

                <div>

                    <div class="fw-semibold">

                        {{ $topic->name }}

                    </div>

                    <small class="text-muted">

                        {{ $topic->subTopics->count() }}
                        {{ Str::plural('Subtopic',$topic->subTopics->count()) }}

                    </small>

                </div>

            </div>

        </button>

    </h2>

    <div
        id="collapse{{ $index }}"
        class="accordion-collapse collapse"
        data-bs-parent="#topicsAccordion">

        <div class="accordion-body">

            @if($topic->subTopics->isEmpty())

                <div class="text-muted">

                    <i class="bi bi-info-circle me-2"></i>

                    No subtopics available under this topic.

                </div>

            @else

                <div class="subtopic-list">

                    @foreach($topic->subTopics as $subTopic)

                        <div class="subtopic-item">

                            <div class="subtopic-name">

                                <i class="bi bi-dot subtopic-icon"></i>

                                {{ $subTopic->name }}

                            </div>

                            <span class="badge bg-light text-secondary">

                                Subtopic

                            </span>

                        </div>

                    @endforeach

                </div>

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
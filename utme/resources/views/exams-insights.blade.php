@extends('layout.master')

@section('title', 'Exam Insights | UTME')

{{-- Banner --}}
@section('banner')
<div class="inner-banner-w3ls text-right d-flex align-items-center">
    <div class="container">
        <h6 class="agileinfo-title">Exam Insights</h6>
        <ol class="breadcrumb-parent d-flex justify-content-end">
            <li class="breadcrumb-nav">
                <a href="/">Home</a>
            </li>
            <li class="breadcrumb-nav active">Insights</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<section class="wthree-row py-5">
    <div class="container py-lg-5">

        {{-- =====================
            EXAM SUMMARY CARDS
        ====================== --}}
        <div class="row mb-5">
            <div class="col-md-3">
                <div class="bg-white shadow rounded p-4 text-center">
                    <p class="text-muted mb-1">Score</p>
                    <h4 class="font-weight-bold">
                        {{ $exam->obtained_score }} / {{ $exam->total_score }}
                    </h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="bg-white shadow rounded p-4 text-center">
                    <p class="text-muted mb-1">Percentage</p>
                    <h4 class="text-primary font-weight-bold">
                        {{ round(($exam->obtained_score / $exam->total_score) * 100, 1) }}%
                    </h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="bg-white shadow rounded p-4 text-center">
                    <p class="text-muted mb-1">Time Spent</p>
                    <h4 class="font-weight-bold">{{ $timeSpent }} mins</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="bg-white shadow rounded p-4 text-center">
                    <p class="text-muted mb-1">Status</p>
                    <h4 class="font-weight-bold 
                        {{ $status === 'Pass' ? 'text-success' : 'text-danger' }}">
                        {{ $status }}
                    </h4>
                </div>
            </div>
        </div>

        {{-- =====================
            TOPIC PERFORMANCE BY SUBJECT
        ====================== --}}
        @if(!empty($topics))
            @foreach($topics as $subject => $subjectTopics)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ $subject }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach($subjectTopics as $topic)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>{{ $topic['name'] }}</strong>
                                    <span class="{{ $topic['accuracy'] >= 70 ? 'text-success' : ($topic['accuracy'] >= 50 ? 'text-warning' : 'text-danger') }}">
                                        {{ $topic['accuracy'] }}%
                                    </span>
                                </div>
                                <small class="text-muted">
                                    {{ $topic['correct'] ?? 0 }} correct out of {{ $topic['total'] ?? 0 }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-muted">No topic insights available for this exam.</p>
        @endif

        {{-- =====================
            AI PERFORMANCE FEEDBACK
        ====================== --}}
      @if(!empty($aiFeedback))
		<div class="card mb-4 shadow-sm">
		    <div class="card-header bg-light">
		        <h5 class="mb-0">🧠 Performance Feedback</h5>
		    </div>
		    <div class="card-body">
		        <ul class="pl-3">
		            @foreach((array) $aiFeedback as $feedback)
		                <li class="mb-2">{{ $feedback }}</li>
		            @endforeach
		        </ul>
		    </div>
		</div>
		@endif


        {{-- =====================
            PERSONALIZED STUDY PLAN
        ====================== --}}
        @if(!empty($plan))
		<div class="card shadow-sm">
		    <div class="card-header bg-light">
		        <h5 class="mb-0">📅 Personalized Study Plan</h5>
		    </div>
		    <div class="card-body">
		        @foreach($plan as $item)
		            <div class="mb-4">
		                <h6 class="text-primary">
		                    {{ $item['time_block'] ?? $item['day'] ?? 'Study Session' }}
		                </h6>
		                <p class="mb-0">
		                    {{ $item['activity'] ?? $item['focus'] ?? '' }}
		                </p>
		            </div>
		        @endforeach
		    </div>
		</div>
		@endif


    </div>
</section>
@endsection

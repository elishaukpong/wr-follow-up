@extends('layouts.app')

@section('title', 'Checked In - ' . $event->title)

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md text-center">
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-green-100 rounded-full mx-auto mb-6 flex items-center justify-center">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <!-- Title -->
        <h1 class="text-2xl font-bold text-gray-900 mb-2">You're Checked In!</h1>

        @if($attendee->member)
            @php
                $visitStatus = $attendee->member->visit_status;
                $visitCount = $attendee->member->visit_count;
                $badgeClasses = match($visitStatus) {
                    'First Timer' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'Second Timer' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'Third Timer' => 'bg-purple-50 text-purple-700 border-purple-200',
                    'Regular' => 'bg-green-50 text-green-700 border-green-200',
                    default => 'bg-gray-50 text-gray-700 border-gray-200',
                };
            @endphp
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border {{ $badgeClasses }} text-sm font-medium mb-6">
                {{ $visitStatus }}
                @if($visitCount > 1)
                    <span class="text-xs opacity-75">&bull; Visit #{{ $visitCount }}</span>
                @endif
            </div>
        @endif

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="text-gray-500 text-sm mb-1">{{ $event->title }}</div>
            <div class="text-xl font-semibold text-gray-900 mb-4">{{ $attendee->name }}</div>

            <div class="text-gray-500 text-sm">
                Checked in at {{ $attendee->checked_in_at->format('g:i A') }}
            </div>
        </div>

        <!-- Message -->
        <p class="text-gray-600 mb-8">
            @if($attendee->member && $attendee->member->visit_count === 1)
                Welcome! We're glad you're here.
            @else
                Great to see you again!
            @endif
        </p>

        <!-- Action -->
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Home
        </a>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Checked In - ' . $event->title)

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md text-center">
        <!-- Success Icon with Animation -->
        <div class="w-24 h-24 bg-green-100 rounded-full mx-auto mb-6 flex items-center justify-center animate-bounce-once">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-900 mb-2">You're Checked In!</h1>

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
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border {{ $badgeClasses }} text-sm font-medium mb-4">
                {{ $visitStatus }}
                @if($visitCount > 1)
                    <span class="text-xs opacity-75">&bull; Visit #{{ $visitCount }}</span>
                @endif
            </div>
        @endif

        <!-- Name -->
        <p class="text-xl text-gray-600 mb-8">
            Welcome, <span class="font-semibold text-gray-900">{{ $attendee->member->name ?? $attendee->name }}</span>!
        </p>

        <!-- Loading indicator -->
        <div class="flex items-center justify-center gap-2 text-gray-500">
            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm">Loading event details...</span>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-once {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-once {
        animation: bounce-once 0.5s ease-out;
    }
</style>

<script>
    // Redirect to event page after 2 seconds
    setTimeout(function() {
        window.location.href = "{{ route('checkin.event', ['uniqueCode' => $event->unique_code, 'a' => $attendee->id]) }}";
    }, 2000);
</script>
@endsection

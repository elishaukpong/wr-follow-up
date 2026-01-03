@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full text-center">
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <div class="w-20 h-20 bg-green-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">Successfully Checked In!</h1>

                @if($attendee->member)
                    @php
                        $visitStatus = $attendee->member->visit_status;
                        $badgeColor = match($visitStatus) {
                            'First Timer' => 'bg-amber-100 text-amber-800',
                            'Second Timer' => 'bg-blue-100 text-blue-800',
                            'Third Timer' => 'bg-purple-100 text-purple-800',
                            'Regular' => 'bg-green-100 text-green-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <div class="inline-block px-4 py-2 rounded-full {{ $badgeColor }} font-semibold text-sm mb-6">
                        {{ $visitStatus }}
                    </div>
                @endif

                <div class="bg-purple-50 rounded-lg p-6 mb-6">
                    <p class="text-sm text-gray-600 mb-2">Event</p>
                    <p class="text-xl font-bold text-purple-900 mb-4">{{ $event->title }}</p>

                    <p class="text-sm text-gray-600 mb-2">Name</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $attendee->name }}</p>

                    @if($attendee->member && $attendee->member->visit_count > 1)
                        <p class="text-sm text-gray-500 mt-4">
                            Total visits: {{ $attendee->member->visit_count }}
                        </p>
                    @endif
                </div>

                <p class="text-gray-600 mb-8">
                    @if($attendee->member && $attendee->member->visit_count === 1)
                        Welcome! We're so glad you're here for the first time.
                    @else
                        Thank you for checking in! Great to see you again.
                    @endif
                </p>

                <a href="{{ route('home') }}"
                    class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-300">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
@endsection

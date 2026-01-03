@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gray-900 text-white">
        <div class="max-w-2xl mx-auto px-4 py-12 text-center">
            <!-- Check-in Confirmation -->
            @if($attendee)
                <div class="inline-flex items-center gap-2 bg-green-500/20 text-green-300 px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    You're checked in, {{ $attendee->member->name ?? $attendee->name }}!
                </div>
            @endif

            <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ $event->title }}</h1>

            <div class="flex flex-wrap items-center justify-center gap-4 text-gray-300">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $event->date->format('l, F j, Y') }}
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $event->time->format('g:i A') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Event Image -->
        @if($event->image)
            <div class="rounded-2xl overflow-hidden mb-8 shadow-sm">
                <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
            </div>
        @endif

        <!-- Details Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <!-- Location -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Location</div>
                        <div class="font-medium text-gray-900">{{ $event->location }}</div>
                    </div>
                </div>
            </div>

            <!-- Date & Time -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Date & Time</div>
                        <div class="font-medium text-gray-900">{{ $event->date->format('l, F j, Y') }}</div>
                        <div class="text-gray-600">{{ $event->time->format('g:i A') }}</div>
                    </div>
                </div>
            </div>

            <!-- Attendance (if checked in) -->
            @if($attendee && $attendee->member)
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Your Check-in</div>
                            <div class="font-medium text-gray-900">{{ $attendee->member->name }}</div>
                            <div class="text-gray-600 text-sm">Checked in at {{ $attendee->checked_in_at->format('g:i A') }}</div>
                            @php
                                $visitStatus = $attendee->member->visit_status;
                                $badgeClasses = match($visitStatus) {
                                    'First Timer' => 'bg-amber-50 text-amber-700',
                                    'Second Timer' => 'bg-blue-50 text-blue-700',
                                    'Third Timer' => 'bg-purple-50 text-purple-700',
                                    'Regular' => 'bg-green-50 text-green-700',
                                    default => 'bg-gray-50 text-gray-700',
                                };
                            @endphp
                            <span class="inline-block mt-2 px-2 py-1 rounded-md text-xs font-medium {{ $badgeClasses }}">
                                {{ $visitStatus }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Description -->
        @if($event->description)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="font-semibold text-gray-900 mb-3">About This Event</h2>
                <div class="text-gray-600 prose prose-sm max-w-none">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>
        @endif

        <!-- Attendee Count -->
        <div class="text-center text-gray-500 text-sm mb-8">
            {{ $event->checked_in_count }} {{ Str::plural('person', $event->checked_in_count) }} checked in
        </div>

        <!-- Social Media -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 text-center">
            <p class="text-gray-600 mb-4">Stay connected with us!</p>

            <div class="flex items-center justify-center gap-4 mb-4">
                @if(config('social.facebook'))
                    <a href="{{ config('social.facebook') }}" target="_blank" rel="noopener"
                       class="w-12 h-12 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center transition-colors">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                @endif

                @if(config('social.whatsapp'))
                    <a href="{{ config('social.whatsapp') }}" target="_blank" rel="noopener"
                       class="w-12 h-12 bg-green-500 hover:bg-green-600 rounded-full flex items-center justify-center transition-colors">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>
                @endif

                @if(config('social.tiktok'))
                    <a href="{{ config('social.tiktok') }}" target="_blank" rel="noopener"
                       class="w-12 h-12 bg-black hover:bg-gray-800 rounded-full flex items-center justify-center transition-colors">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                        </svg>
                    </a>
                @endif
            </div>

            <p class="text-sm text-gray-500">Follow us for updates & inspiration</p>
        </div>
    </div>
</div>
@endsection

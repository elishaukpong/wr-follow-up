<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Worship Realm</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gray-50">

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md border-b border-gray-100 z-50">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="font-bold text-xl text-gray-900">Worship Realm</div>
            <a href="/admin" class="text-sm text-gray-500 hover:text-gray-900 transition-colors">
                Admin
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-6">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                Welcome to<br>
                <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">Worship Realm</span>
            </h1>
            <p class="text-xl text-gray-600 mb-10 max-w-xl mx-auto">
                A place of worship, community, and spiritual growth. Join us as we journey together in faith.
            </p>

            @if($upcomingEvent)
                <a href="{{ route('checkin.show', $upcomingEvent->unique_code) }}"
                   class="inline-flex items-center gap-3 px-8 py-4 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-xl transition-colors">
                    <span>Join Our Next Event</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            @endif
        </div>
    </section>

    <!-- Upcoming Event -->
    @if($upcomingEvent)
        <section class="py-16 px-6">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-6 text-center">Upcoming Event</h2>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    @if($upcomingEvent->image)
                        <div class="h-48 bg-gray-100">
                            <img src="{{ Storage::url($upcomingEvent->image) }}" alt="{{ $upcomingEvent->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="p-8">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $upcomingEvent->date->format('l, F j, Y') }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $upcomingEvent->time->format('g:i A') }}
                            </span>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $upcomingEvent->title }}</h3>

                        <div class="flex items-center gap-2 text-gray-600 mb-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $upcomingEvent->location }}
                        </div>

                        @if($upcomingEvent->description)
                            <p class="text-gray-600 mb-6">{{ Str::limit($upcomingEvent->description, 150) }}</p>
                        @endif

                        <a href="{{ route('checkin.show', $upcomingEvent->unique_code) }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-xl transition-colors">
                            Check In Now
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- About Section -->
    <section class="py-16 px-6 bg-white border-t border-gray-100">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">About Us</h2>
            <p class="text-lg text-gray-600 mb-8">
                Worship Realm is more than just a gathering—it's a family. We believe in the power of worship,
                the strength of community, and the transforming love of God. Whether you're new to faith or
                have been walking this journey for years, there's a place for you here.
            </p>

            <div class="grid md:grid-cols-3 gap-6 mt-12">
                <div class="p-6">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Love</h3>
                    <p class="text-gray-600 text-sm">Experience genuine love and acceptance in our community.</p>
                </div>

                <div class="p-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Community</h3>
                    <p class="text-gray-600 text-sm">Build lasting friendships and grow together in faith.</p>
                </div>

                <div class="p-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Purpose</h3>
                    <p class="text-gray-600 text-sm">Discover your calling and live a life of meaning.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Media Section -->
    <section class="py-16 px-6">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Stay Connected</h2>
            <p class="text-gray-600 mb-8">Follow us on social media for updates, inspiration, and community.</p>

            <div class="flex items-center justify-center gap-4">
                @if(config('social.facebook'))
                    <a href="{{ config('social.facebook') }}" target="_blank" rel="noopener"
                       class="w-14 h-14 bg-white border border-gray-200 hover:border-blue-500 hover:bg-blue-50 rounded-xl flex items-center justify-center transition-all group">
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                @endif

                @if(config('social.whatsapp'))
                    <a href="{{ config('social.whatsapp') }}" target="_blank" rel="noopener"
                       class="w-14 h-14 bg-white border border-gray-200 hover:border-green-500 hover:bg-green-50 rounded-xl flex items-center justify-center transition-all group">
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>
                @endif

                @if(config('social.tiktok'))
                    <a href="{{ config('social.tiktok') }}" target="_blank" rel="noopener"
                       class="w-14 h-14 bg-white border border-gray-200 hover:border-gray-900 hover:bg-gray-50 rounded-xl flex items-center justify-center transition-all group">
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                        </svg>
                    </a>
                @endif

                @if(config('social.instagram'))
                    <a href="{{ config('social.instagram') }}" target="_blank" rel="noopener"
                       class="w-14 h-14 bg-white border border-gray-200 hover:border-pink-500 hover:bg-pink-50 rounded-xl flex items-center justify-center transition-all group">
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                @endif

                @if(config('social.youtube'))
                    <a href="{{ config('social.youtube') }}" target="_blank" rel="noopener"
                       class="w-14 h-14 bg-white border border-gray-200 hover:border-red-500 hover:bg-red-50 rounded-xl flex items-center justify-center transition-all group">
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-red-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 px-6 border-t border-gray-200">
        <div class="max-w-3xl mx-auto text-center">
            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} Worship Realm. All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>

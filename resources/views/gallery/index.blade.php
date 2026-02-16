<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Worship Realm Gallery - Relive moments of worship and encounter.">

    <title>Gallery | Worship Realm</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ============================================
           CSS CUSTOM PROPERTIES
           ============================================ */
        :root {
            --color-bg: #0a0a0f;
            --color-bg-secondary: #12121a;
            --color-surface: rgba(255, 255, 255, 0.03);
            --color-surface-hover: rgba(255, 255, 255, 0.06);
            --color-border: rgba(255, 255, 255, 0.08);
            --color-border-hover: rgba(255, 255, 255, 0.15);
            --color-text: #ffffff;
            --color-text-secondary: rgba(255, 255, 255, 0.7);
            --color-text-tertiary: rgba(255, 255, 255, 0.5);
            --color-accent: #d4a853;
            --color-accent-light: #f0d48a;
            --gradient-purple: #6366f1;
            --gradient-violet: #8b5cf6;
            --gradient-fuchsia: #d946ef;
            --gradient-amber: #f59e0b;
            --gradient-rose: #f43f5e;
            --spacing-xs: 0.5rem;
            --spacing-sm: 1rem;
            --spacing-md: 1.5rem;
            --spacing-lg: 2rem;
            --spacing-xl: 3rem;
            --spacing-2xl: 4rem;
            --spacing-3xl: 6rem;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-serif: 'Playfair Display', Georgia, serif;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-full: 9999px;
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 300ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 500ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ============================================
           BASE RESET
           ============================================ */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            font-size: 16px;
        }

        body {
            font-family: var(--font-sans);
            background: var(--color-bg);
            color: var(--color-text);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            cursor: pointer;
            border: none;
            background: none;
            font-family: inherit;
        }

        /* ============================================
           ANIMATED GRADIENT BACKGROUND
           ============================================ */
        .gradient-bg {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            overflow: hidden;
        }

        .gradient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            animation: float 20s ease-in-out infinite;
        }

        .orb-1 {
            width: 600px;
            height: 600px;
            background: linear-gradient(135deg, var(--gradient-purple), var(--gradient-violet));
            top: -200px;
            right: -100px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, var(--gradient-fuchsia), var(--gradient-rose));
            bottom: -150px;
            left: -100px;
            animation-delay: -5s;
        }

        .orb-3 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, var(--gradient-amber), var(--color-accent));
            top: 40%;
            left: 30%;
            animation-delay: -10s;
            opacity: 0.3;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.05); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(-30px, -20px) scale(1.02); }
        }

        .noise-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
            opacity: 0.03;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
        }

        /* ============================================
           LAYOUT
           ============================================ */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--spacing-lg);
        }

        /* ============================================
           NAVIGATION
           ============================================ */
        .nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: var(--spacing-md) 0;
            background: rgba(10, 10, 15, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--color-border);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
        }

        .nav-logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--color-accent), var(--gradient-amber));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.875rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: var(--spacing-lg);
        }

        .nav-link {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-text-secondary);
            transition: color var(--transition-fast);
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--color-text);
        }

        .nav-link.active {
            color: var(--color-accent);
        }

        .nav-cta {
            padding: var(--spacing-xs) var(--spacing-md);
            background: var(--color-text);
            color: var(--color-bg);
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: var(--radius-full);
            transition: transform var(--transition-fast), box-shadow var(--transition-fast);
        }

        .nav-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(255, 255, 255, 0.15);
        }

        .nav-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            padding: 5px;
        }

        .nav-toggle span {
            width: 24px;
            height: 2px;
            background: var(--color-text);
            border-radius: 2px;
            transition: all var(--transition-fast);
        }

        /* ============================================
           PAGE HEADER
           ============================================ */
        .page-header {
            padding: calc(80px + var(--spacing-3xl)) 0 var(--spacing-2xl);
            text-align: center;
        }

        .page-header .section-label {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-xs);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--color-accent);
            margin-bottom: var(--spacing-sm);
        }

        .page-header .section-title {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: var(--spacing-sm);
        }

        .page-header .section-subtitle {
            font-size: 1.125rem;
            color: var(--color-text-secondary);
            max-width: 500px;
            margin: 0 auto;
        }

        /* ============================================
           EVENT FILTER PILLS
           ============================================ */
        .filter-pills {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: var(--spacing-xs);
            margin-bottom: var(--spacing-2xl);
            padding: 0 var(--spacing-lg);
        }

        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-full);
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--color-text-secondary);
            transition: all var(--transition-fast);
            cursor: pointer;
        }

        .filter-pill:hover {
            background: var(--color-surface-hover);
            border-color: var(--color-border-hover);
            color: var(--color-text);
        }

        .filter-pill.active {
            background: rgba(212, 168, 83, 0.15);
            border-color: rgba(212, 168, 83, 0.4);
            color: var(--color-accent);
        }

        .filter-pill-count {
            font-size: 0.7rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 1px 7px;
            border-radius: var(--radius-full);
        }

        .filter-pill.active .filter-pill-count {
            background: rgba(212, 168, 83, 0.2);
        }

        /* ============================================
           MASONRY GALLERY GRID
           ============================================ */
        .gallery-grid {
            columns: 3;
            column-gap: var(--spacing-md);
            padding-bottom: var(--spacing-3xl);
        }

        .gallery-item {
            break-inside: avoid;
            margin-bottom: var(--spacing-md);
            border-radius: var(--radius-lg);
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: transform var(--transition-base);
        }

        .gallery-item:hover {
            transform: translateY(-4px);
        }

        .gallery-item img {
            width: 100%;
            display: block;
            transition: transform var(--transition-slow);
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        .gallery-item-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 50%);
            opacity: 0;
            transition: opacity var(--transition-base);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: var(--spacing-md);
        }

        .gallery-item:hover .gallery-item-overlay {
            opacity: 1;
        }

        .gallery-item-event {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--color-accent);
            margin-bottom: 4px;
        }

        .gallery-item-caption {
            font-size: 0.9rem;
            color: var(--color-text);
            line-height: 1.4;
        }

        /* ============================================
           EMPTY STATE
           ============================================ */
        .empty-state {
            text-align: center;
            padding: var(--spacing-3xl) var(--spacing-lg);
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto var(--spacing-lg);
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-text-tertiary);
        }

        .empty-state-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: var(--spacing-xs);
        }

        .empty-state-text {
            color: var(--color-text-tertiary);
            max-width: 400px;
            margin: 0 auto;
        }

        /* ============================================
           LIGHTBOX
           ============================================ */
        .lightbox-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.95);
            z-index: 200;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-lg);
        }

        .lightbox-content {
            position: relative;
            max-width: 90vw;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .lightbox-content img {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: var(--radius-md);
        }

        .lightbox-caption {
            margin-top: var(--spacing-sm);
            text-align: center;
            max-width: 600px;
        }

        .lightbox-caption-event {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--color-accent);
            margin-bottom: 4px;
        }

        .lightbox-caption-text {
            font-size: 1rem;
            color: var(--color-text-secondary);
        }

        .lightbox-close {
            position: absolute;
            top: -40px;
            right: 0;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-text-secondary);
            transition: color var(--transition-fast);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .lightbox-close:hover {
            color: var(--color-text);
            background: rgba(255, 255, 255, 0.2);
        }

        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-text-secondary);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all var(--transition-fast);
        }

        .lightbox-nav:hover {
            color: var(--color-text);
            background: rgba(255, 255, 255, 0.2);
        }

        .lightbox-prev {
            left: -70px;
        }

        .lightbox-next {
            right: -70px;
        }

        /* ============================================
           FOOTER
           ============================================ */
        .footer {
            padding: var(--spacing-xl) 0;
            border-top: 1px solid var(--color-border);
        }

        .footer-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-logo {
            font-weight: 700;
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
        }

        .footer-tagline {
            font-family: var(--font-serif);
            font-style: italic;
            color: var(--color-text-tertiary);
        }

        .footer-copyright {
            font-size: 0.875rem;
            color: var(--color-text-tertiary);
        }

        /* ============================================
           ANIMATIONS
           ============================================ */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 1024px) {
            .gallery-grid {
                columns: 2;
            }

            .lightbox-prev { left: -55px; }
            .lightbox-next { right: -55px; }
        }

        @media (max-width: 768px) {
            :root {
                --spacing-3xl: 4rem;
            }

            .nav-links {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: var(--color-bg);
                flex-direction: column;
                justify-content: center;
                gap: var(--spacing-lg);
                opacity: 0;
                visibility: hidden;
                transition: all var(--transition-base);
            }

            .nav-links.open {
                opacity: 1;
                visibility: visible;
            }

            .nav-links a {
                font-size: 1.5rem;
            }

            .nav-toggle {
                display: flex;
                z-index: 101;
            }

            .nav-toggle.active span:nth-child(1) {
                transform: translateY(7px) rotate(45deg);
            }

            .nav-toggle.active span:nth-child(2) {
                opacity: 0;
            }

            .nav-toggle.active span:nth-child(3) {
                transform: translateY(-7px) rotate(-45deg);
            }

            .gallery-grid {
                columns: 1;
            }

            .lightbox-prev { left: 10px; }
            .lightbox-next { right: 10px; }
            .lightbox-nav { width: 40px; height: 40px; }

            .footer-inner {
                flex-direction: column;
                gap: var(--spacing-md);
                text-align: center;
            }
        }
    </style>
</head>
<body x-data="galleryApp()" @keydown.escape.window="closeLightbox()" @keydown.arrow-left.window="prevImage()" @keydown.arrow-right.window="nextImage()">
    <!-- ============================================
         ANIMATED BACKGROUND
    ============================================= -->
    <div class="gradient-bg">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
    </div>
    <div class="noise-overlay"></div>

    <!-- ============================================
         NAVIGATION
    ============================================= -->
    <nav class="nav">
        <div class="container">
            <div class="nav-inner">
                <a href="{{ route('home') }}" class="nav-logo">
                    <span class="nav-logo-icon">WR</span>
                    Worship Realm
                </a>

                <div class="nav-links" id="navLinks">
                    <a href="{{ route('home') }}#about" class="nav-link">About</a>
                    <a href="{{ route('home') }}#expect" class="nav-link">What to Expect</a>
                    <a href="{{ route('gallery') }}" class="nav-link active">Gallery</a>
                    <a href="{{ route('home') }}#connect" class="nav-link">Connect</a>
                </div>

                <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- ============================================
         PAGE HEADER
    ============================================= -->
    <section class="page-header">
        <div class="container">
            <p class="section-label">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
                Moments Captured
            </p>
            <h1 class="section-title">Gallery</h1>
            <p class="section-subtitle">Relive the worship, the encounters, and the moments that shaped our journey.</p>
        </div>
    </section>

    <!-- ============================================
         EVENT FILTER PILLS
    ============================================= -->
    @if($events->count() > 0)
    <div class="container">
        <div class="filter-pills">
            <a href="{{ route('gallery') }}" class="filter-pill {{ !$selectedEvent ? 'active' : '' }}">
                All Events
                <span class="filter-pill-count">{{ $images->count() }}</span>
            </a>
            @foreach($events as $event)
                <a href="{{ route('gallery', ['event' => $event->id]) }}" class="filter-pill {{ $selectedEvent && $selectedEvent->id === $event->id ? 'active' : '' }}">
                    {{ $event->title }}
                    <span class="filter-pill-count">{{ $event->gallery_images_count }}</span>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ============================================
         GALLERY GRID
    ============================================= -->
    <div class="container">
        @if($images->count() > 0)
            <div class="gallery-grid animate-on-scroll">
                @foreach($images as $index => $image)
                    <div class="gallery-item" @click="openLightbox({{ $index }})">
                        <img src="{{ $image->image_url }}" alt="{{ $image->caption ?? 'Gallery photo' }}" loading="lazy">
                        <div class="gallery-item-overlay">
                            <span class="gallery-item-event">{{ $image->event->title }}</span>
                            @if($image->caption)
                                <span class="gallery-item-caption">{{ $image->caption }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state animate-on-scroll">
                <div class="empty-state-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
                <h3 class="empty-state-title">No photos yet</h3>
                <p class="empty-state-text">Photos from our worship gatherings will appear here. Stay tuned!</p>
            </div>
        @endif
    </div>

    <!-- ============================================
         LIGHTBOX
    ============================================= -->
    <template x-if="lightboxOpen">
        <div class="lightbox-backdrop" @click.self="closeLightbox()" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="lightbox-content">
                <button class="lightbox-close" @click="closeLightbox()" aria-label="Close lightbox">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>

                <button class="lightbox-nav lightbox-prev" @click.stop="prevImage()" aria-label="Previous image" x-show="images.length > 1">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </button>

                <img :src="currentImage.url" :alt="currentImage.caption || 'Gallery photo'">

                <button class="lightbox-nav lightbox-next" @click.stop="nextImage()" aria-label="Next image" x-show="images.length > 1">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </button>

                <div class="lightbox-caption" x-show="currentImage.event || currentImage.caption">
                    <p class="lightbox-caption-event" x-text="currentImage.event" x-show="currentImage.event"></p>
                    <p class="lightbox-caption-text" x-text="currentImage.caption" x-show="currentImage.caption"></p>
                </div>
            </div>
        </div>
    </template>

    <!-- ============================================
         FOOTER
    ============================================= -->
    <footer class="footer">
        <div class="container">
            <div class="footer-inner">
                <div class="footer-logo">
                    <span>Worship Realm</span>
                    <span class="footer-tagline">— All glory to God.</span>
                </div>
                <p class="footer-copyright">&copy; {{ date('Y') }} Worship Realm. A worship movement.</p>
            </div>
        </div>
    </footer>

    <!-- ============================================
         SCRIPTS
    ============================================= -->
    <script>
        function galleryApp() {
            return {
                lightboxOpen: false,
                currentIndex: 0,
                images: [
                    @foreach($images as $image)
                    {
                        url: @js($image->image_url),
                        caption: @js($image->caption),
                        event: @js($image->event->title)
                    },
                    @endforeach
                ],

                get currentImage() {
                    return this.images[this.currentIndex] || { url: '', caption: '', event: '' };
                },

                openLightbox(index) {
                    this.currentIndex = index;
                    this.lightboxOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                closeLightbox() {
                    this.lightboxOpen = false;
                    document.body.style.overflow = '';
                },

                nextImage() {
                    if (!this.lightboxOpen) return;
                    this.currentIndex = (this.currentIndex + 1) % this.images.length;
                },

                prevImage() {
                    if (!this.lightboxOpen) return;
                    this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                }
            };
        }

        // Mobile menu toggle
        const navToggle = document.getElementById('navToggle');
        const navLinks = document.getElementById('navLinks');

        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navLinks.classList.toggle('open');
        });

        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('active');
                navLinks.classList.remove('open');
            });
        });

        // Scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
    </script>
</body>
</html>

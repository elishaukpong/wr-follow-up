<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Worship Realm - A worship movement centered on deep, Spirit-led thanksgiving and encounters with God.">

    <title>Worship Realm | Worship of Thanksgiving</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

    <style>
        /* ============================================
           CSS CUSTOM PROPERTIES
           ============================================ */
        :root {
            /* Colors - Stripe-inspired with worship warmth */
            --color-bg: #0a0a0f;
            --color-bg-secondary: #12121a;
            --color-surface: rgba(255, 255, 255, 0.03);
            --color-surface-hover: rgba(255, 255, 255, 0.06);
            --color-border: rgba(255, 255, 255, 0.08);
            --color-border-hover: rgba(255, 255, 255, 0.15);

            --color-text: #ffffff;
            --color-text-secondary: rgba(255, 255, 255, 0.7);
            --color-text-tertiary: rgba(255, 255, 255, 0.5);

            /* Accent colors - Gold/Amber for worship */
            --color-accent: #d4a853;
            --color-accent-light: #f0d48a;

            /* Gradient colors */
            --gradient-purple: #6366f1;
            --gradient-violet: #8b5cf6;
            --gradient-fuchsia: #d946ef;
            --gradient-amber: #f59e0b;
            --gradient-rose: #f43f5e;

            /* Spacing */
            --spacing-xs: 0.5rem;
            --spacing-sm: 1rem;
            --spacing-md: 1.5rem;
            --spacing-lg: 2rem;
            --spacing-xl: 3rem;
            --spacing-2xl: 4rem;
            --spacing-3xl: 6rem;

            /* Typography */
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-serif: 'Playfair Display', Georgia, serif;

            /* Border radius */
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-full: 9999px;

            /* Transitions */
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 300ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 500ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-bounce: 500ms cubic-bezier(0.34, 1.56, 0.64, 1);
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
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            25% {
                transform: translate(30px, -30px) scale(1.05);
            }
            50% {
                transform: translate(-20px, 20px) scale(0.95);
            }
            75% {
                transform: translate(-30px, -20px) scale(1.02);
            }
        }

        /* Noise texture overlay */
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

        .container-narrow {
            max-width: 800px;
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
            transition: background var(--transition-base), backdrop-filter var(--transition-base);
        }

        .nav.scrolled {
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

        .nav-link:hover {
            color: var(--color-text);
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

        /* Mobile Nav */
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
           HERO SECTION
           ============================================ */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: calc(80px + var(--spacing-3xl)) var(--spacing-lg) var(--spacing-3xl);
            text-align: center;
            position: relative;
        }

        .hero-content {
            max-width: 900px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-xs);
            padding: var(--spacing-xs) var(--spacing-md);
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-full);
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            margin-bottom: var(--spacing-lg);
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-badge-dot {
            width: 8px;
            height: 8px;
            background: var(--color-accent);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }

        .hero-title {
            font-size: clamp(4rem, 15vw, 10rem);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.04em;
            margin-bottom: var(--spacing-lg);
            animation: fadeInUp 0.8s ease-out 0.1s both;
        }

        .hero-title-line {
            display: block;
        }

        .hero-title-gradient {
            background: linear-gradient(135deg, var(--color-accent-light), var(--color-accent), var(--gradient-amber));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Text Switcher Animation */
        .text-switcher {
            position: relative;
            display: inline-block;
            min-width: 100%;
        }

        .text-switcher-item {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            opacity: 0;
            transform: translateY(30px) rotateX(-20deg);
            transition: opacity 0.6s ease, transform 0.6s ease;
            background: linear-gradient(135deg, var(--color-accent-light), var(--color-accent), var(--gradient-amber));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .text-switcher-item.active {
            position: relative;
            opacity: 1;
            transform: translateY(0) rotateX(0deg);
        }

        .text-switcher-item.exit {
            opacity: 0;
            transform: translateY(-30px) rotateX(20deg);
        }

        .hero-scripture {
            font-family: var(--font-serif);
            font-size: 1.125rem;
            font-style: italic;
            color: var(--color-text-tertiary);
            margin-bottom: var(--spacing-md);
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .hero-description {
            font-size: 1.25rem;
            color: var(--color-text-secondary);
            max-width: 600px;
            margin: 0 auto var(--spacing-xl);
            line-height: 1.7;
            animation: fadeInUp 0.8s ease-out 0.3s both;
        }

        .hero-cta {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-md);
            justify-content: center;
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        /* Scroll indicator */
        .scroll-indicator {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-text-tertiary);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            animation: fadeIn 1s ease-out 1s both;
            z-index: 50;
            transition: opacity 0.4s ease;
        }

        .scroll-indicator.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .scroll-indicator-line {
            width: 1px;
            height: 24px;
            background: linear-gradient(to bottom, var(--color-text-tertiary), transparent);
            animation: scrollPulse 2s ease-in-out infinite;
        }

        @keyframes scrollPulse {
            0%, 100% { transform: scaleY(1); opacity: 1; }
            50% { transform: scaleY(0.5); opacity: 0.5; }
        }

        /* ============================================
           BUTTONS
           ============================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-xs);
            padding: 0.75rem var(--spacing-lg);
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: var(--radius-full);
            transition: all var(--transition-fast);
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--color-accent), var(--gradient-amber));
            color: var(--color-bg);
            box-shadow: 0 4px 20px rgba(212, 168, 83, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 40px rgba(212, 168, 83, 0.4);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
            opacity: 0;
            transition: opacity var(--transition-fast);
        }

        .btn-primary:hover::before {
            opacity: 1;
        }

        .btn-secondary {
            background: var(--color-surface);
            color: var(--color-text);
            border: 1px solid var(--color-border);
        }

        .btn-secondary:hover {
            background: var(--color-surface-hover);
            border-color: var(--color-border-hover);
            transform: translateY(-2px);
        }

        .btn-icon {
            transition: transform var(--transition-fast);
        }

        .btn:hover .btn-icon {
            transform: translateX(4px);
        }

        /* ============================================
           SCRIPTURE SECTION
           ============================================ */
        .scripture-section {
            padding: var(--spacing-3xl) 0;
            position: relative;
        }

        .scripture-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xl);
            padding: var(--spacing-2xl);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .scripture-card::before {
            content: '"';
            position: absolute;
            top: -20px;
            left: 40px;
            font-family: var(--font-serif);
            font-size: 12rem;
            color: var(--color-accent);
            opacity: 0.1;
            line-height: 1;
        }

        .scripture-text {
            font-family: var(--font-serif);
            font-size: clamp(1.5rem, 4vw, 2.25rem);
            font-weight: 400;
            font-style: italic;
            line-height: 1.5;
            margin-bottom: var(--spacing-md);
            position: relative;
            z-index: 1;
        }

        .scripture-ref {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--color-accent);
        }

        /* ============================================
           UPCOMING EVENT SECTION
           ============================================ */
        .event-section {
            padding: var(--spacing-3xl) 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: var(--spacing-2xl);
        }

        .section-label {
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

        .section-title {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .event-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xl);
            overflow: hidden;
            transition: all var(--transition-base);
        }

        .event-card:hover {
            border-color: var(--color-border-hover);
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .event-image {
            height: 240px;
            background: linear-gradient(135deg, var(--gradient-purple), var(--gradient-violet));
            position: relative;
            overflow: hidden;
        }

        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow);
        }

        .event-card:hover .event-image img {
            transform: scale(1.05);
        }

        .event-content {
            padding: var(--spacing-xl);
        }

        .event-meta {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-sm);
            margin-bottom: var(--spacing-md);
        }

        .event-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: rgba(212, 168, 83, 0.1);
            border: 1px solid rgba(212, 168, 83, 0.2);
            border-radius: var(--radius-full);
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--color-accent);
        }

        .event-tag-secondary {
            background: var(--color-surface);
            border-color: var(--color-border);
            color: var(--color-text-secondary);
        }

        .event-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: var(--spacing-xs);
            letter-spacing: -0.01em;
        }

        .event-location {
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
            color: var(--color-text-tertiary);
            margin-bottom: var(--spacing-md);
        }

        .event-description {
            color: var(--color-text-secondary);
            margin-bottom: var(--spacing-lg);
            line-height: 1.7;
        }

        /* ============================================
           ABOUT SECTION
           ============================================ */
        .about-section {
            padding: var(--spacing-3xl) 0;
            background: var(--color-bg-secondary);
            position: relative;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-3xl);
            align-items: center;
        }

        .about-content h2 {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: var(--spacing-lg);
            line-height: 1.2;
        }

        .about-content h2 span {
            color: var(--color-text-tertiary);
        }

        .about-text {
            font-size: 1.125rem;
            color: var(--color-text-secondary);
            line-height: 1.8;
            margin-bottom: var(--spacing-md);
        }

        .about-values {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-sm);
            margin-top: var(--spacing-xl);
        }

        .value-tag {
            padding: var(--spacing-xs) var(--spacing-md);
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-full);
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--color-text-secondary);
            transition: all var(--transition-fast);
        }

        .value-tag:hover {
            border-color: var(--color-accent);
            color: var(--color-accent);
        }

        /* Visual block */
        .about-visual {
            position: relative;
        }

        .about-visual-block {
            aspect-ratio: 1;
            background: linear-gradient(135deg, var(--gradient-purple), var(--gradient-violet), var(--gradient-fuchsia));
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .about-visual-block::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.2), transparent);
        }

        .about-visual-text {
            font-size: 8rem;
            font-weight: 800;
            color: rgba(255, 255, 255, 0.15);
            letter-spacing: 0.1em;
        }

        .about-visual-float {
            position: absolute;
            width: 120px;
            height: 120px;
            background: var(--color-accent);
            border-radius: var(--radius-lg);
            bottom: -30px;
            right: -30px;
            animation: floatSmall 6s ease-in-out infinite;
        }

        @keyframes floatSmall {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-10px, -10px) rotate(5deg); }
        }

        /* ============================================
           WHAT TO EXPECT SECTION
           ============================================ */
        .expect-section {
            padding: var(--spacing-3xl) 0;
        }

        .expect-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--spacing-md);
        }

        .expect-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: var(--spacing-xl);
            transition: all var(--transition-base);
            position: relative;
            overflow: hidden;
        }

        .expect-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--color-accent), var(--gradient-amber));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform var(--transition-base);
        }

        .expect-card:hover {
            border-color: var(--color-border-hover);
            transform: translateY(-4px);
        }

        .expect-card:hover::before {
            transform: scaleX(1);
        }

        .expect-number {
            font-family: var(--font-serif);
            font-size: 2rem;
            font-weight: 400;
            color: var(--color-accent);
            margin-bottom: var(--spacing-md);
        }

        .expect-title {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: var(--spacing-sm);
        }

        .expect-text {
            font-size: 0.9rem;
            color: var(--color-text-tertiary);
            line-height: 1.6;
        }

        /* ============================================
           CTA SECTION
           ============================================ */
        .cta-section {
            padding: var(--spacing-3xl) 0;
        }

        .cta-card {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: var(--radius-xl);
            padding: var(--spacing-3xl);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-card::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--color-accent), transparent 70%);
            opacity: 0.1;
        }

        .cta-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--color-accent);
            margin-bottom: var(--spacing-md);
        }

        .cta-title {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: var(--spacing-md);
        }

        .cta-text {
            font-size: 1.125rem;
            color: var(--color-text-secondary);
            max-width: 500px;
            margin: 0 auto var(--spacing-xl);
            line-height: 1.7;
        }

        /* ============================================
           SOCIAL SECTION
           ============================================ */
        .social-section {
            padding: var(--spacing-3xl) 0;
            background: var(--color-bg-secondary);
        }

        .social-grid {
            display: flex;
            justify-content: center;
            gap: var(--spacing-md);
            flex-wrap: wrap;
        }

        .social-link {
            width: 56px;
            height: 56px;
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-text-secondary);
            transition: all var(--transition-fast);
        }

        .social-link:hover {
            border-color: var(--color-accent);
            color: var(--color-accent);
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(212, 168, 83, 0.2);
        }

        .social-link svg {
            width: 24px;
            height: 24px;
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
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Scroll animations */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .animate-delay-1 { transition-delay: 0.1s; }
        .animate-delay-2 { transition-delay: 0.2s; }
        .animate-delay-3 { transition-delay: 0.3s; }
        .animate-delay-4 { transition-delay: 0.4s; }

        /* ============================================
           GALLERY PREVIEW SECTION
           ============================================ */
        .gallery-preview-section {
            padding: var(--spacing-3xl) 0;
        }

        .gallery-preview-grid {
            columns: 4;
            column-gap: var(--spacing-md);
        }

        .gallery-preview-item {
            break-inside: avoid;
            margin-bottom: var(--spacing-md);
            border-radius: var(--radius-lg);
            overflow: hidden;
            position: relative;
            transition: transform var(--transition-base);
        }

        .gallery-preview-item:hover {
            transform: translateY(-4px);
        }

        .gallery-preview-item img {
            width: 100%;
            display: block;
            transition: transform var(--transition-slow);
        }

        .gallery-preview-item:hover img {
            transform: scale(1.05);
        }

        .gallery-preview-item-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 50%);
            opacity: 0;
            transition: opacity var(--transition-base);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: var(--spacing-sm);
        }

        .gallery-preview-item:hover .gallery-preview-item-overlay {
            opacity: 1;
        }

        .gallery-preview-item-event {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--color-accent);
            margin-bottom: 2px;
        }

        .gallery-preview-item-caption {
            font-size: 0.8rem;
            color: var(--color-text);
            line-height: 1.3;
        }

        .gallery-preview-cta {
            text-align: center;
            margin-top: var(--spacing-xl);
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 1024px) {
            .about-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-2xl);
            }

            .about-visual {
                order: -1;
                max-width: 400px;
                margin: 0 auto;
            }

            .expect-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .gallery-preview-grid {
                columns: 3;
            }
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

            .hero {
                padding-top: calc(60px + var(--spacing-2xl));
            }

            .hero-cta {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
            }

            .expect-grid {
                grid-template-columns: 1fr;
            }

            .gallery-preview-grid {
                columns: 2;
            }

            .footer-inner {
                flex-direction: column;
                gap: var(--spacing-md);
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 3.25rem;
            }

            .about-visual-text {
                font-size: 5rem;
            }

            .text-switcher-item {
                transition: opacity 0.4s ease, transform 0.4s ease;
            }

            .gallery-preview-grid {
                columns: 1;
            }
        }
    </style>
</head>
<body>
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
    <nav class="nav" id="nav">
        <div class="container">
            <div class="nav-inner">
                <a href="#" class="nav-logo">
                    <span class="nav-logo-icon">WR</span>
                    Worship Realm
                </a>

                <div class="nav-links" id="navLinks">
                    <a href="#about" class="nav-link">About</a>
                    <a href="#expect" class="nav-link">What to Expect</a>
                    <a href="{{ route('gallery') }}" class="nav-link">Gallery</a>
                    <a href="#connect" class="nav-link">Connect</a>
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
         HERO SECTION
    ============================================= -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    A Worship Movement
                </div>

                <h1 class="hero-title">
                    <span class="hero-title-line">Sounds of</span>
                    <span class="hero-title-line text-switcher py-4" id="textSwitcher">
                        <span class="text-switcher-item active">Thanksgiving</span>
                        <span class="text-switcher-item">Surrender</span>
                        <span class="text-switcher-item">Deliverance</span>
                        <span class="text-switcher-item">Victory</span>
                    </span>
                </h1>

                <p class="hero-scripture">
                    "I will sing unto the Lord, for He has triumphed gloriously." — Exodus 15
                </p>

{{--                <p class="hero-description hidden">--}}
{{--                    We gather not with empty hands, but hearts overflowing. <br>--}}
{{--                    Join us as we pour out worship and encounter the presence of God together.--}}
{{--                </p>--}}

                <div class="hero-cta">
                    @if($upcomingEvent)
                        <a href="{{ route('checkin.show', $upcomingEvent->unique_code) }}" class="btn btn-primary">
                            Join the Worship
                            <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <a href="#connect" class="btn btn-primary">
                            Join the Movement
                            <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                    <a href="#about" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>

        <div class="scroll-indicator">
            <span>Scroll</span>
            <div class="scroll-indicator-line"></div>
        </div>
    </section>

    <!-- ============================================
         SCRIPTURE SECTION
    ============================================= -->
    <section class="scripture-section">
        <div class="container container-narrow">
            <div class="scripture-card animate-on-scroll">
                <p class="scripture-text">
                    I will sing unto the Lord, for He has triumphed gloriously;<br>
                    the horse and his rider He has thrown into the sea.
                </p>
                <span class="scripture-ref">The Song of Moses — Exodus 15:1</span>
            </div>
        </div>
    </section>

    <!-- ============================================
         UPCOMING EVENT
    ============================================= -->
    @if($upcomingEvent)
    <section class="event-section" id="event">
        <div class="container container-narrow">
            <div class="section-header animate-on-scroll">
                <p class="section-label">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Upcoming Event
                </p>
                <h2 class="section-title">Don't Miss This</h2>
            </div>

            <div class="event-card animate-on-scroll animate-delay-1">
                @if($upcomingEvent->image)
                <div class="event-image">
                    <img src="{{ Storage::url($upcomingEvent->image) }}" alt="{{ $upcomingEvent->title }}">
                </div>
                @else
                <div class="event-image"></div>
                @endif

                <div class="event-content">
                    <div class="event-meta">
                        <span class="event-tag">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            {{ $upcomingEvent->date->format('l, F j, Y') }}
                        </span>
                        <span class="event-tag event-tag-secondary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            {{ $upcomingEvent->time->format('g:i A') }}
                        </span>
                    </div>

                    <h3 class="event-title">{{ $upcomingEvent->title }}</h3>

                    <p class="event-location">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        {{ $upcomingEvent->location }}
                    </p>

                    @if($upcomingEvent->description)
                    <p class="event-description">{{ Str::limit($upcomingEvent->description, 200) }}</p>
                    @endif

                    <a href="{{ route('checkin.show', $upcomingEvent->unique_code) }}" class="btn btn-primary">
                        Check In Now
                        <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- ============================================
         ABOUT SECTION
    ============================================= -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-content animate-on-scroll">
                    <h2>
                        We are a worship movement.<br>
                        <span>Rooted in encounter. Anchored in Scripture.</span>
                    </h2>

                    <p class="about-text">
                        Worship Realm exists to cultivate spaces where hearts can pour out thanksgiving—unfiltered, Spirit-led, and deeply personal. We believe worship is more than music; it is response. It is remembrance. It is war cry and whisper.
                    </p>

                    <p class="about-text">
                        Here, we don't perform. We encounter. We carry a sound of gratitude that echoes the victory song of those who have crossed over.
                    </p>

                    <div class="about-values">
                        <span class="value-tag">Encounter</span>
                        <span class="value-tag">Gratitude</span>
                        <span class="value-tag">Community</span>
                        <span class="value-tag">Spirit-Led</span>
                    </div>
                </div>

                <div class="about-visual animate-on-scroll animate-delay-2">
                    <div class="about-visual-block">
                        <span class="about-visual-text">WR</span>
                    </div>
                    <div class="about-visual-float"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         WHAT TO EXPECT
    ============================================= -->
    <section class="expect-section" id="expect">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <p class="section-label">The Experience</p>
                <h2 class="section-title">What to Expect</h2>
            </div>

            <div class="expect-grid">
                <div class="expect-card animate-on-scroll animate-delay-1">
                    <span class="expect-number">I</span>
                    <h3 class="expect-title">Spirit-Led Worship</h3>
                    <p class="expect-text">No scripts. No rigid structures. We follow where the Spirit leads, creating space for authentic expression.</p>
                </div>

                <div class="expect-card animate-on-scroll animate-delay-2">
                    <span class="expect-number">II</span>
                    <h3 class="expect-title">Deep Thanksgiving</h3>
                    <p class="expect-text">Intentional moments to remember, to recount His faithfulness, and to offer the sacrifice of praise.</p>
                </div>

                <div class="expect-card animate-on-scroll animate-delay-3">
                    <span class="expect-number">III</span>
                    <h3 class="expect-title">Creative Sounds</h3>
                    <p class="expect-text">Bold expressions. Prophetic melodies. A fusion of the ancient and the new—fresh yet timeless.</p>
                </div>

                <div class="expect-card animate-on-scroll animate-delay-4">
                    <span class="expect-number">IV</span>
                    <h3 class="expect-title">Sacred Atmosphere</h3>
                    <p class="expect-text">We guard the environment. Reverence meets freedom. Come as you are—leave transformed.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
         GALLERY PREVIEW
    ============================================= -->
    @if(isset($galleryPreview) && $galleryPreview->count() > 0)
    <section class="gallery-preview-section" id="gallery">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <p class="section-label">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                    Moments Captured
                </p>
                <h2 class="section-title">Gallery</h2>
            </div>

            <div class="gallery-preview-grid animate-on-scroll animate-delay-1">
                @foreach($galleryPreview as $photo)
                    <div class="gallery-preview-item">
                        <img src="{{ $photo->image_url }}" alt="{{ $photo->caption ?? 'Gallery photo' }}" loading="lazy">
                        <div class="gallery-preview-item-overlay">
                            <span class="gallery-preview-item-event">{{ $photo->event->title }}</span>
                            @if($photo->caption)
                                <span class="gallery-preview-item-caption">{{ $photo->caption }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="gallery-preview-cta animate-on-scroll animate-delay-2">
                <a href="{{ route('gallery') }}" class="btn btn-secondary">
                    View All Photos
                    <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- ============================================
         CTA SECTION
    ============================================= -->
    <section class="cta-section" id="connect">
        <div class="container container-narrow">
            <div class="cta-card animate-on-scroll">
                <p class="cta-label">The Invitation</p>
                <h2 class="cta-title">Will You Join the Sound?</h2>
                <p class="cta-text">
                    This is your invitation to step into a realm of worship. To lift your voice in thanksgiving. To be part of something eternal.
                </p>
                @if($upcomingEvent)
                    <a href="{{ route('checkin.show', $upcomingEvent->unique_code) }}" class="btn btn-primary">
                        Be Part of WR
                        <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <a href="#about" class="btn btn-primary">
                        Learn More
                        <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- ============================================
         SOCIAL SECTION
    ============================================= -->
    <section class="social-section">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <p class="section-label">Stay Connected</p>
                <h2 class="section-title">Follow the Journey</h2>
            </div>

            <div class="social-grid animate-on-scroll animate-delay-1">
                @if(config('social.facebook'))
                <a href="{{ config('social.facebook') }}" target="_blank" rel="noopener" class="social-link" aria-label="Facebook">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                @endif

                @if(config('social.instagram'))
                <a href="{{ config('social.instagram') }}" target="_blank" rel="noopener" class="social-link" aria-label="Instagram">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
                @endif

                @if(config('social.youtube'))
                <a href="{{ config('social.youtube') }}" target="_blank" rel="noopener" class="social-link" aria-label="YouTube">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                </a>
                @endif

                @if(config('social.tiktok'))
                <a href="{{ config('social.tiktok') }}" target="_blank" rel="noopener" class="social-link" aria-label="TikTok">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                    </svg>
                </a>
                @endif

                @if(config('social.whatsapp'))
                <a href="{{ config('social.whatsapp') }}" target="_blank" rel="noopener" class="social-link" aria-label="WhatsApp">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </a>
                @endif
            </div>
        </div>
    </section>

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
        // Navigation scroll effect & scroll indicator
        const nav = document.getElementById('nav');
        const scrollIndicator = document.querySelector('.scroll-indicator');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }

            // Hide scroll indicator after scrolling
            if (window.scrollY > 100) {
                scrollIndicator.classList.add('hidden');
            } else {
                scrollIndicator.classList.remove('hidden');
            }
        });

        // Mobile menu toggle
        const navToggle = document.getElementById('navToggle');
        const navLinks = document.getElementById('navLinks');

        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navLinks.classList.toggle('open');
        });

        // Close menu on link click
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('active');
                navLinks.classList.remove('open');
            });
        });

        // Scroll animations
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Text Switcher Animation
        const textSwitcher = document.getElementById('textSwitcher');
        const textItems = textSwitcher.querySelectorAll('.text-switcher-item');
        let currentIndex = 0;
        const switchInterval = 3000; // Switch every 3 seconds

        function switchText() {
            // Get current and next items
            const currentItem = textItems[currentIndex];
            const nextIndex = (currentIndex + 1) % textItems.length;
            const nextItem = textItems[nextIndex];

            // Animate out current item
            currentItem.classList.remove('active');
            currentItem.classList.add('exit');

            // Animate in next item
            nextItem.classList.add('active');

            // Clean up exit class after animation
            setTimeout(() => {
                currentItem.classList.remove('exit');
            }, 600);

            // Update index
            currentIndex = nextIndex;
        }

        // Start the text switching after initial delay
        setTimeout(() => {
            setInterval(switchText, switchInterval);
        }, 2000);
    </script>
</body>
</html>

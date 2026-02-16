<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Check In - {{ $event->title }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0f172a;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            cursor: none;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 40px;
            padding: 40px;
        }

        .header {
            text-align: center;
        }

        .brand {
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .event-title {
            font-size: 36px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 12px;
        }

        .event-meta {
            font-size: 18px;
            color: #94a3b8;
        }

        .qr-wrapper {
            background: white;
            border-radius: 24px;
            padding: 32px;
            display: inline-block;
        }

        .qr-code svg {
            display: block;
            width: 360px;
            height: 360px;
        }

        .instructions {
            text-align: center;
        }

        .instructions-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .instructions-subtitle {
            font-size: 18px;
            color: #94a3b8;
        }

        .pulse-ring {
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.2); }
            50% { box-shadow: 0 0 0 20px rgba(255, 255, 255, 0); }
        }

        /* Scale up for larger displays */
        @media (min-height: 900px) {
            .qr-code svg {
                width: 420px;
                height: 420px;
            }

            .event-title { font-size: 42px; }
            .instructions-title { font-size: 32px; }
        }

        @media (min-height: 1080px) {
            .container { gap: 56px; }

            .qr-code svg {
                width: 480px;
                height: 480px;
            }

            .qr-wrapper { padding: 40px; border-radius: 32px; }
            .event-title { font-size: 48px; }
            .instructions-title { font-size: 36px; }
            .instructions-subtitle { font-size: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">Worship Realm</div>
            <h1 class="event-title">{{ $event->title }}</h1>
            <div class="event-meta">
                {{ $event->date->format('l, F j, Y') }} &middot; {{ $event->time->format('g:i A') }}
            </div>
        </div>

        <div class="qr-wrapper pulse-ring">
            <div class="qr-code">
                {!! $qrCode !!}
            </div>
        </div>

        <div class="instructions">
            <div class="instructions-title">Scan to Check In</div>
            <div class="instructions-subtitle">Point your phone camera at the QR code</div>
        </div>
    </div>
</body>
</html>

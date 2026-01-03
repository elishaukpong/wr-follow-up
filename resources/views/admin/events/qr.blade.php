<!DOCTYPE html>
<html>
<head>
    <title>QR Code - {{ $event->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 40px;
            font-family: 'Inter', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            border-radius: 24px;
            padding: 48px;
            text-align: center;
            box-shadow: 0 25px 80px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
        }
        .logo {
            font-size: 14px;
            font-weight: 600;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 24px;
        }
        h1 {
            color: #1a202c;
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 700;
        }
        .event-info {
            color: #718096;
            margin-bottom: 32px;
            font-size: 16px;
            line-height: 1.6;
        }
        .event-info strong {
            color: #4a5568;
        }
        .qr-wrapper {
            background: #f7fafc;
            border-radius: 16px;
            padding: 24px;
            margin: 0 auto 24px;
            display: inline-block;
        }
        .qr-code {
            background: white;
            padding: 16px;
            border-radius: 12px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .qr-code svg {
            display: block;
        }
        .scan-text {
            font-size: 18px;
            font-weight: 600;
            color: #5a67d8;
            margin-top: 24px;
            margin-bottom: 8px;
        }
        .scan-subtext {
            font-size: 14px;
            color: #a0aec0;
        }
        .url-box {
            background: #edf2f7;
            padding: 16px;
            border-radius: 12px;
            word-break: break-all;
            color: #4a5568;
            font-size: 13px;
            margin-top: 24px;
            text-align: left;
        }
        .url-label {
            font-size: 11px;
            font-weight: 600;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 32px;
        }
        .btn {
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .btn-primary {
            background: #5a67d8;
            color: white;
        }
        .btn-primary:hover {
            background: #4c51bf;
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: #edf2f7;
            color: #4a5568;
        }
        .btn-secondary:hover {
            background: #e2e8f0;
        }

        /* Print-optimized styles */
        @media print {
            @page {
                size: A4;
                margin: 0;
            }
            body {
                background: white;
                padding: 0;
                display: block;
                min-height: auto;
            }
            .container {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
                padding: 60px;
                page-break-inside: avoid;
            }
            .buttons, .url-box {
                display: none;
            }
            .qr-wrapper {
                padding: 40px;
            }
            .qr-code svg {
                width: 300px !important;
                height: 300px !important;
            }
            h1 {
                font-size: 36px;
            }
            .event-info {
                font-size: 20px;
            }
            .scan-text {
                font-size: 24px;
                margin-top: 32px;
            }
            .scan-subtext {
                font-size: 18px;
            }
        }

        /* Print-only footer */
        .print-footer {
            display: none;
        }
        @media print {
            .print-footer {
                display: block;
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #e2e8f0;
                font-size: 14px;
                color: #a0aec0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Worship Realm</div>

        <h1>{{ $event->title }}</h1>
        <div class="event-info">
            <strong>{{ $event->date->format('l, F j, Y') }}</strong><br>
            {{ $event->time->format('g:i A') }} &bull; {{ $event->location }}
        </div>

        <div class="qr-wrapper">
            <div class="qr-code">
                {!! $qrCode !!}
            </div>
        </div>

        <div class="scan-text">Scan to Check In</div>
        <div class="scan-subtext">Open your camera and point at the QR code</div>

        <div class="url-box">
            <div class="url-label">Or visit this URL</div>
            {{ $checkInUrl }}
        </div>

        <div class="buttons">
            <button class="btn btn-primary" onclick="window.print()">
                Print QR Code
            </button>
            <button class="btn btn-secondary" onclick="window.close()">
                Close
            </button>
        </div>

        <div class="print-footer">
            {{ $checkInUrl }}
        </div>
    </div>
</body>
</html>

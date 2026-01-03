<!DOCTYPE html>
<html>
<head>
    <title>QR Code - {{ $event->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            max-width: 440px;
            width: 100%;
            overflow: hidden;
        }

        .header {
            padding: 32px 32px 24px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .brand {
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
        }

        .event-title {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
            margin-bottom: 8px;
        }

        .event-meta {
            color: #64748b;
            font-size: 15px;
        }

        .event-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .event-meta span::before {
            content: '';
            width: 4px;
            height: 4px;
            background: #cbd5e1;
            border-radius: 50%;
        }

        .event-meta span:first-child::before {
            display: none;
        }

        .qr-section {
            padding: 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .qr-wrapper {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            display: inline-block;
        }

        .qr-code {
            display: block;
        }

        .qr-code svg {
            display: block;
            width: 240px;
            height: 240px;
        }

        .scan-instruction {
            margin-top: 24px;
            text-align: center;
        }

        .scan-title {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .scan-subtitle {
            font-size: 14px;
            color: #94a3b8;
        }

        .url-section {
            padding: 0 32px 32px;
        }

        .url-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
        }

        .url-label {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .url-text {
            font-size: 13px;
            color: #475569;
            word-break: break-all;
            font-family: ui-monospace, monospace;
        }

        .actions {
            padding: 24px 32px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 12px;
        }

        .btn {
            flex: 1;
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.15s ease;
            text-decoration: none;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: #0f172a;
            color: white;
        }

        .btn-primary:hover {
            background: #1e293b;
        }

        .btn-secondary {
            background: white;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #f1f5f9;
        }

        .btn svg {
            width: 18px;
            height: 18px;
        }

        /* Print styles */
        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            body {
                background: white;
                padding: 0;
                display: block;
            }

            .container {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }

            .header {
                padding: 48px 48px 32px;
            }

            .event-title {
                font-size: 32px;
            }

            .event-meta {
                font-size: 18px;
            }

            .qr-section {
                padding: 48px;
            }

            .qr-code svg {
                width: 320px !important;
                height: 320px !important;
            }

            .scan-title {
                font-size: 24px;
            }

            .scan-subtitle {
                font-size: 16px;
            }

            .url-section,
            .actions {
                display: none;
            }

            .print-footer {
                display: block !important;
                padding: 32px 48px;
                text-align: center;
                border-top: 1px solid #e2e8f0;
            }

            .print-footer-url {
                font-size: 14px;
                color: #64748b;
                font-family: ui-monospace, monospace;
            }
        }

        .print-footer {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">Worship Realm</div>
            <h1 class="event-title">{{ $event->title }}</h1>
            <div class="event-meta">
                <span>{{ $event->date->format('l, F j, Y') }}</span>
                <span>{{ $event->time->format('g:i A') }}</span>
            </div>
        </div>

        <div class="qr-section">
            <div class="qr-wrapper">
                <div class="qr-code">
                    {!! $qrCode !!}
                </div>
            </div>

            <div class="scan-instruction">
                <div class="scan-title">Scan to Check In</div>
                <div class="scan-subtitle">Point your camera at the QR code</div>
            </div>
        </div>

        <div class="url-section">
            <div class="url-box">
                <div class="url-label">Or visit this URL</div>
                <div class="url-text">{{ $checkInUrl }}</div>
            </div>
        </div>

        <div class="actions">
            <button class="btn btn-primary" onclick="window.print()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print
            </button>
            <a href="{{ route('checkin.kiosk', $event->unique_code) }}" class="btn btn-secondary" target="_blank">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Kiosk
            </a>
            <button class="btn btn-secondary" onclick="window.close()">
                Close
            </button>
        </div>

        <div class="print-footer">
            <div class="print-footer-url">{{ $checkInUrl }}</div>
        </div>
    </div>
</body>
</html>

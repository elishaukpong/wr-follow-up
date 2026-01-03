<?php

namespace App\Http\Controllers;

use App\Models\Event;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventQRController extends Controller
{
    public function show(Event $event)
    {
        $checkInUrl = route('checkin.show', $event->unique_code);

        // Generate QR code
        $qrCode = QrCode::size(400)
            ->margin(2)
            ->generate($checkInUrl);

        return view('admin.events.qr', [
            'event' => $event,
            'qrCode' => $qrCode,
            'checkInUrl' => $checkInUrl,
        ]);
    }
}

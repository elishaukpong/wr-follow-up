<?php

use App\Models\Event;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventQRController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\GalleryController;

// Homepage - Simple landing page
Route::get('/', function () {
    $upcomingEvent = Event::where('status', 'published')
        ->where('date', '>=', today())
        ->orderBy('date')
        ->first();

    $galleryPreview = \App\Models\GalleryImage::where('is_featured', true)
        ->with('event')
        ->orderBy('sort_order')
        ->limit(8)
        ->get();

    return view('home', compact('upcomingEvent', 'galleryPreview'));
})->name('home');

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

// Check-in Routes (public - for attendees)
Route::get('/event/{uniqueCode}', [CheckInController::class, 'event'])->name('checkin.event');
Route::get('/event/{uniqueCode}/checkin', [CheckInController::class, 'show'])->name('checkin.show');
Route::post('/event/{uniqueCode}/checkin', [CheckInController::class, 'store'])->name('checkin.store');
Route::post('/event/{uniqueCode}/checkin/lookup', [CheckInController::class, 'lookup'])->name('checkin.lookup');
Route::get('/event/{uniqueCode}/checkin/{attendee}/success', [CheckInController::class, 'success'])->name('checkin.success');
Route::get('/event/{uniqueCode}/kiosk', [CheckInController::class, 'kiosk'])->name('checkin.kiosk');

// Admin QR Code Display
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/events/{event}/qr', [EventQRController::class, 'show'])->name('admin.events.qr');
});

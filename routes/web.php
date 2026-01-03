<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventQRController;
use App\Http\Controllers\CheckInController;

// Homepage - Simple landing page
Route::get('/', function () {
    return view('home');
})->name('home');

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

<?php

use App\Http\Controllers\Running\AnnouncementController;
use App\Http\Controllers\Running\EventController;
use App\Http\Controllers\Running\RsvpController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Running Club Routes
|--------------------------------------------------------------------------
| Events and announcements are publicly visible.
| RSVPs require an authenticated, verified account.
|--------------------------------------------------------------------------
*/

Route::prefix('running')->name('running.')->group(function () {

    // Public — visible to all visitors
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');

    // Authenticated customers only
    Route::middleware(['auth', 'verified'])->group(function () {

        // RSVPs
        Route::post('/events/{event}/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');
        Route::delete('/events/{event}/rsvp', [RsvpController::class, 'destroy'])->name('rsvp.destroy');

        // Customer's own RSVP history
        Route::get('/my-rsvps', [RsvpController::class, 'index'])->name('rsvp.index');
    });
});
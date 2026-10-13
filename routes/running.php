<?php

use App\Http\Controllers\Running\AccountController;
use App\Http\Controllers\Running\AnnouncementController;
use App\Http\Controllers\Running\EventController;
use App\Http\Controllers\Running\Manage\AnnouncementController as ManageAnnouncementController;
use App\Http\Controllers\Running\Manage\EventController as ManageEventController;
use App\Http\Controllers\Running\RsvpController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Running Club Routes
|--------------------------------------------------------------------------
| Runs, events and announcements are public.
| RSVPs need a signed-in account with a verified email.
| /running/manage is for admins: the admin dashboard links to it.
|--------------------------------------------------------------------------
*/

Route::prefix('running')->name('running.')->group(function () {

    // Public — visible to all visitors
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');

    // Sign in or register, then come back to the run club page
    Route::get('/sign-in', [AccountController::class, 'signIn'])->name('sign-in');
    Route::get('/join', [AccountController::class, 'register'])->name('join');

    // Authenticated customers only
    Route::middleware(['auth', 'verified'])->group(function () {

        // RSVPs (the same POST joins a run or changes the number of extra runners)
        Route::post('/events/{event}/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');
        Route::delete('/events/{event}/rsvp', [RsvpController::class, 'destroy'])->name('rsvp.destroy');

        // Customer's own RSVPs
        Route::get('/my-rsvps', [RsvpController::class, 'index'])->name('rsvp.index');
    });

    // Admin management pages
    Route::prefix('manage')
        ->name('manage.')
        ->middleware(['auth', 'verified', 'role:admin'])
        ->group(function () {
            Route::get('/', [ManageEventController::class, 'index'])->name('index');
            Route::get('/events/create', [ManageEventController::class, 'create'])->name('events.create');
            Route::post('/events', [ManageEventController::class, 'store'])->name('events.store');
            Route::get('/events/{event}/edit', [ManageEventController::class, 'edit'])->name('events.edit');
            Route::put('/events/{event}', [ManageEventController::class, 'update'])->name('events.update');
            Route::patch('/events/{event}/cancel', [ManageEventController::class, 'cancel'])->name('events.cancel');
            Route::delete('/events/{event}', [ManageEventController::class, 'destroy'])->name('events.destroy');
            Route::get('/events/{event}/rsvps', [ManageEventController::class, 'rsvps'])->name('events.rsvps');
            Route::get('/events/{event}/rsvps/export', [ManageEventController::class, 'export'])->name('events.rsvps.export');

            Route::resource('announcements', ManageAnnouncementController::class)->except('show');
        });
});

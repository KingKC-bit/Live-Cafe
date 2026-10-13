<?php

namespace App\Http\Controllers\Running;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * The public run club pages. Anyone can browse runs; only the RSVP actions
 * need an account.
 *
 * Neither page shows who is going or how many: only admins see numbers.
 */
class EventController extends Controller
{
    public function index(Request $request): View
    {
        $events = Event::query()
            ->upcoming()
            ->chronological()
            ->with('photo')
            ->get();

        // The signed-in member's own RSVPs, keyed by event, so each row can
        // say "Going" without a query per row.
        $user = $request->user();

        $myRsvps = $user
            ? Rsvp::query()
                ->where('user_id', $user->id)
                ->active()
                ->whereIn('event_id', $events->modelKeys())
                ->get()
                ->keyBy('event_id')
            : collect();

        $announcement = Announcement::query()->published()->forDisplay()->with('event')->first();
        $announcementCount = Announcement::query()->published()->count();

        return view('running.index', compact('events', 'myRsvps', 'announcement', 'announcementCount'));
    }

    public function show(Request $request, Event $event): View
    {
        $event->load('photo');

        $user = $request->user();

        $myRsvp = $user
            ? $event->rsvps()->where('user_id', $user->id)->first()
            : null;

        return view('running.show', compact('event', 'myRsvp'));
    }
}

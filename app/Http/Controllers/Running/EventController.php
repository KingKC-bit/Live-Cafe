<?php

namespace App\Http\Controllers\Running;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::where('event_date', '>=', now()->toDateString())
            ->with('rsvps')
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->get();

        $announcements = Announcement::latest()->take(5)->get();

        return view('running.index', compact('events', 'announcements'));
    }

    public function show(Event $event): View
    {
        // Placeholder — build the event detail / RSVP view next
        $event->load('rsvps.user');
        abort(404, 'Event detail page not yet built.');
    }
}
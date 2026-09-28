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
        $event->load('rsvps.user');
        return view('running.show', compact('event'));
    }
}
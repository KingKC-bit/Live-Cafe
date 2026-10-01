<?php

namespace App\Http\Controllers\Running;

use App\Exceptions\RsvpException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Running\RsvpRequest;
use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use App\Services\RunningClub\RunningClubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RsvpController extends Controller
{
    public function __construct(
        private readonly RunningClubService $runningClub,
    ) {}

    /**
     * The member's upcoming RSVPs.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $rsvps = Rsvp::query()
            ->where('user_id', $user->id)
            ->active()
            ->whereHas('event', fn ($query) => $query->upcoming())
            ->with('event.photo')
            ->get()
            ->sortBy(fn (Rsvp $rsvp) => $rsvp->event->startsAt())
            ->values();

        return view('running.my-rsvps', compact('rsvps'));
    }

    /**
     * Join a run, or change the number of extra runners.
     */
    public function store(RsvpRequest $request, Event $event): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        try {
            $joined = $this->runningClub->saveRsvp($user, $event, $request->extras());
        } catch (RsvpException $exception) {
            return $this->backToEvent($event)->with('error', $exception->getMessage());
        }

        $message = $joined
            ? "You're going! We've emailed you the details."
            : 'Your RSVP is updated.';

        return $this->backToEvent($event)->with('success', $message);
    }

    public function destroy(Request $request, Event $event): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        try {
            $this->runningClub->cancelRsvp($user, $event);
        } catch (RsvpException $exception) {
            return $this->backToEvent($event)->with('error', $exception->getMessage());
        }

        return $this->backToEvent($event)->with('success', 'Your RSVP is cancelled.');
    }

    private function backToEvent(Event $event): RedirectResponse
    {
        return redirect()->to(route('running.events.show', $event).'#rsvp');
    }
}

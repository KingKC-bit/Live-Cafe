<?php

namespace App\Services\RunningClub;

use App\Exceptions\RsvpException;
use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use App\Notifications\RunningClub\RsvpConfirmed;
use App\Notifications\RunningClub\RunCancelled;
use App\Notifications\RunningClub\RunChanged;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

/**
 * The run club's RSVP rules, kept in one place so the member pages and the
 * admin pages can't disagree about them.
 *
 *  - New RSVPs (and extra runners) close 8 hours before the start.
 *  - After that, members can still cancel or bring fewer people until the start.
 *  - Cancelling keeps the row and flips its status, so history is preserved.
 */
class RunningClubService
{
    /**
     * Join a run, re-join after cancelling, or change the number of extra runners.
     *
     * Returns true when the member has just joined (or re-joined), false when
     * they only changed their extras.
     *
     * @throws RsvpException
     */
    public function saveRsvp(User $user, Event $event, int $extras): bool
    {
        $joined = DB::transaction(function () use ($user, $event, $extras): bool {
            // Lock the event row so RSVPs for the same run are handled one at a
            // time. Otherwise two requests could each count the RSVPs before the
            // other one saved, and the stored headcount would miss one of them.
            $event = Event::query()->lockForUpdate()->findOrFail($event->id);

            $rsvp = Rsvp::query()
                ->where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->first();

            $joining = $rsvp === null || ! $rsvp->isGoing();

            $this->ensureRsvpsCanChange($event);

            // After the cutoff, members who are already going can still bring
            // fewer people. Joining or adding people would make the numbers the
            // organisers planned for too low, so that waits for the next run.
            if (! $event->rsvpIsOpen()) {
                if ($rsvp === null || ! $rsvp->isGoing()) {
                    throw RsvpException::closed($event);
                }

                if ($extras > $rsvp->extras) {
                    throw RsvpException::cannotAddExtras($event);
                }
            }

            $rsvp ??= new Rsvp(['user_id' => $user->id, 'event_id' => $event->id]);
            $rsvp->extras = $extras;
            $rsvp->status = Rsvp::STATUS_GOING;
            $rsvp->cancelled_at = null;
            $rsvp->save();

            $event->refreshAttendance();

            return $joining;
        });

        if ($joined) {
            $user->notify(new RsvpConfirmed($event, $extras));
        }

        return $joined;
    }

    /**
     * @throws RsvpException
     */
    public function cancelRsvp(User $user, Event $event): void
    {
        DB::transaction(function () use ($user, $event): void {
            $event = Event::query()->lockForUpdate()->findOrFail($event->id);

            $rsvp = Rsvp::query()
                ->where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->active()
                ->first();

            if ($rsvp === null) {
                throw RsvpException::notGoing($event);
            }

            $this->ensureRsvpsCanChange($event);

            $rsvp->status = Rsvp::STATUS_CANCELLED;
            $rsvp->cancelled_at = now()->toImmutable();
            $rsvp->save();

            $event->refreshAttendance();
        });
    }

    /**
     * Save an admin's edits. Members who are going get an email when the
     * date, start time or location changes, because those decide whether
     * they turn up at the right place at the right time.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function updateEvent(Event $event, array $attributes): void
    {
        $event->fill($attributes);

        $changes = [];

        if ($event->isDirty('event_date')) {
            $changes['Date'] = $event->event_date->format('l j F Y');
        }

        if ($event->isDirty('event_time')) {
            $changes['Start time'] = $event->startTimeLabel();
        }

        if ($event->isDirty('address')) {
            $changes['Location'] = $event->address;
        }

        $event->save();

        if ($changes !== [] && ! $event->isCancelled() && ! $event->hasStarted()) {
            Notification::send($this->membersGoing($event), new RunChanged($event, $changes));
        }
    }

    /**
     * Cancel a run without deleting it, so its RSVPs stay on record, and let
     * everyone who was going know.
     */
    public function cancelEvent(Event $event): void
    {
        if ($event->isCancelled()) {
            return;
        }

        $event->status = Event::STATUS_CANCELLED;
        $event->cancelled_at = now()->toImmutable();
        $event->save();

        Notification::send($this->membersGoing($event), new RunCancelled($event));
    }

    /**
     * @throws RsvpException
     */
    private function ensureRsvpsCanChange(Event $event): void
    {
        if ($event->isCancelled()) {
            throw RsvpException::eventCancelled($event);
        }

        if ($event->hasStarted()) {
            throw RsvpException::eventStarted($event);
        }
    }

    /**
     * @return Collection<int, User>
     */
    private function membersGoing(Event $event): Collection
    {
        return User::query()
            ->whereIn('id', $event->activeRsvps()->select('user_id'))
            ->get();
    }
}

<?php

namespace App\Notifications\RunningClub;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent when a member RSVPs (or re-joins after cancelling).
 *
 * ShouldQueue puts the email on the queue instead of sending it during the
 * request, so the RSVP page answers straight away and a slow or failing mail
 * server is retried by the queue worker instead of breaking the RSVP.
 */
class RsvpConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Event $event,
        public int $extras,
    ) {}

    /**
     * @return list<string>
     */
    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $event = $this->event;

        $message = (new MailMessage)
            ->subject("You're in: {$event->title}, {$event->event_date->format('D j M')}")
            ->greeting("Hi {$notifiable->name},")
            ->line("Thanks for your RSVP to {$event->title}.")
            ->line("**When:** {$event->event_date->format('l j F Y')} at {$event->startTimeLabel()}")
            ->line("**Where:** {$event->address}");

        if ($event->distanceLabel() !== null) {
            $message->line("**Distance:** {$event->distanceLabel()}");
        }

        if ($event->dress_code) {
            $message->line("**Dress code:** {$event->dress_code}");
        }

        if ($this->extras > 0) {
            $message->line("You said you're bringing {$this->extras} {$event->extrasNoun($this->extras)}.");
        }

        return $message
            ->action('View the '.$event->noun(), route('running.events.show', $event))
            ->line("Plans changed? You can cancel or change how many people you're bringing on the {$event->noun()} page.");
    }
}

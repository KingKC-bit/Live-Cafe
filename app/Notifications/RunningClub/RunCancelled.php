<?php

namespace App\Notifications\RunningClub;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to members who are going when an admin cancels a run.
 */
class RunCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Event $event,
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

        return (new MailMessage)
            ->subject("Cancelled: {$event->title} on {$event->event_date->format('D j M')}")
            ->greeting("Hi {$notifiable->name},")
            ->line("{$event->title} on {$event->event_date->format('l j F Y')} at {$event->startTimeLabel()} has been cancelled.")
            ->line('Sorry for the change of plans. Keep an eye on the run club page for what\'s coming up next.')
            ->action('See upcoming runs and events', route('running.index'));
    }
}

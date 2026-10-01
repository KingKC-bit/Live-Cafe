<?php

namespace App\Notifications\RunningClub;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to members who are going when an admin changes the date, start time
 * or location of a run.
 */
class RunChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array<string, string>  $changes  label => new value, e.g. ['Start time' => '07:30']
     */
    public function __construct(
        public Event $event,
        public array $changes,
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
        $message = (new MailMessage)
            ->subject("Update: {$this->event->title} has changed")
            ->greeting("Hi {$notifiable->name},")
            ->line("The details for {$this->event->title}, which you RSVP'd to, have changed:");

        foreach ($this->changes as $label => $value) {
            $message->line("**{$label}:** {$value}");
        }

        return $message
            ->action('View the '.strtolower($this->event->typeLabel()), route('running.events.show', $this->event))
            ->line("Can't make it any more? You can cancel your RSVP on the ".strtolower($this->event->typeLabel()).' page.');
    }
}

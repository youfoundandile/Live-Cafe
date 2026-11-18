<?php

namespace App\Notifications\RunningClub;

use App\Models\Event;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent immediately when a member RSVPs (or re-joins after cancelling) on the
 * Run Club portal, matching Fortify's synchronous auth mail delivery so
 * confirmation emails arrive without requiring a background queue worker.
 */
class RsvpConfirmed extends Notification
{
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

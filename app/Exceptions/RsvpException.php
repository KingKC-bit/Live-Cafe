<?php

namespace App\Exceptions;

use App\Models\Event;
use RuntimeException;

/**
 * An RSVP change the club's rules don't allow. The message is written for
 * the member and shown on the run page.
 *
 * The wording stays neutral on purpose: an RSVP only helps the organisers
 * estimate numbers, so the messages say what the member can still do and
 * never that someone can't come.
 */
class RsvpException extends RuntimeException
{
    public static function eventCancelled(Event $event): self
    {
        return new self('This '.self::noun($event).' has been cancelled, so RSVPs are closed.');
    }

    public static function eventStarted(Event $event): self
    {
        return new self('This '.self::noun($event).' has already started, so RSVPs can no longer be changed.');
    }

    public static function closed(Event $event): self
    {
        return new self('RSVPs for this '.self::noun($event).' closed at '.self::closingTime($event).'.');
    }

    public static function cannotAddExtras(Event $event): self
    {
        return new self('RSVPs closed at '.self::closingTime($event).'. You can still bring fewer people or cancel your RSVP.');
    }

    public static function notGoing(Event $event): self
    {
        return new self("You don't have an RSVP for this ".self::noun($event).'.');
    }

    private static function noun(Event $event): string
    {
        return strtolower($event->typeLabel());
    }

    private static function closingTime(Event $event): string
    {
        return $event->rsvpClosesAt()->format('H:i \o\n D j M');
    }
}

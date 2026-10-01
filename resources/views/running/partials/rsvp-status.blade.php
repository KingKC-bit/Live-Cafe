{{-- The status shown next to a run: cancelled, the member's own RSVP, or whether RSVPs are open. --}}
@if ($event->isCancelled())
    <span class="rc-badge rc-badge-cancelled">Cancelled</span>
@elseif ($myRsvp?->isGoing())
    <span class="rc-badge rc-badge-going">Going{{ $myRsvp->extras > 0 ? ' +'.$myRsvp->extras : '' }}</span>
@elseif ($event->rsvpIsOpen())
    <span class="rc-badge rc-badge-open">Open</span>
    <span class="rc-closes">Closes {{ $event->rsvpClosesAt()->format('H:i D') }}</span>
@else
    <span class="rc-badge rc-badge-closed">Closed</span>
@endif

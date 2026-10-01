{{--
    Shortcuts to the management pages for admins, so they never need the
    admin dashboard to get there. Members and guests don't see it.
    Pass $event on a run's page to get links for that run.
--}}
@if (auth()->user()?->isAdmin())
    <div class="rc-admin-bar" role="region" aria-label="Admin shortcuts">
        <p class="rc-admin-bar-label">Only admins see this</p>
        <div class="rc-admin-bar-actions">
            @isset($event)
                <a href="{{ route('running.manage.events.edit', $event) }}" class="rc-btn rc-btn-primary rc-btn-small">Edit this {{ $event->noun() }}</a>
                <a href="{{ route('running.manage.events.rsvps', $event) }}" class="rc-btn rc-btn-outline rc-btn-small">See RSVPs</a>
            @else
                <a href="{{ route('running.manage.events.create') }}" class="rc-btn rc-btn-primary rc-btn-small">Add a run or event</a>
                <a href="{{ route('running.manage.announcements.create') }}" class="rc-btn rc-btn-outline rc-btn-small">Write an announcement</a>
            @endisset
            <a href="{{ route('running.manage.index') }}" class="rc-link">Manage everything</a>
        </div>
    </div>
@endif

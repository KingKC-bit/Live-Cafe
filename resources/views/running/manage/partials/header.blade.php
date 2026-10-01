{{--
    Header and tabs for the management pages.
    Expects $title and $active ('events' or 'announcements'); $actionUrl and
    $actionLabel add a button on the right.
--}}
<header class="rc-manage-head">
    <div>
        <p class="rc-eyebrow">Run club admin</p>
        <h1 class="rc-page-title">{{ $title }}</h1>
    </div>
    @isset($actionUrl)
        <a href="{{ $actionUrl }}" class="rc-btn rc-btn-primary">{{ $actionLabel }}</a>
    @endisset
</header>

<nav class="rc-tabs" aria-label="Run club admin">
    <a href="{{ route('running.manage.index') }}" class="rc-tab {{ $active === 'events' ? 'is-active' : '' }}">Runs &amp; events</a>
    <a href="{{ route('running.manage.announcements.index') }}" class="rc-tab {{ $active === 'announcements' ? 'is-active' : '' }}">Announcements</a>
    <a href="{{ route('running.index') }}" class="rc-tab rc-tab-public">View the public page &rarr;</a>
</nav>

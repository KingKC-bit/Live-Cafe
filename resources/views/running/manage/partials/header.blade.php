{{--
    Header and tabs for the management pages.
    Expects $title and $active ('events' or 'announcements'). $actions adds
    buttons on the right: a list of [url, label], the first one highlighted.
--}}
<header class="rc-manage-head">
    <div>
        <p class="rc-eyebrow">Live Cafe admin</p>
        <h1 class="rc-page-title">{{ $title }}</h1>
    </div>
    @if (! empty($actions))
        <div class="rc-manage-actions">
            @foreach ($actions as [$url, $label])
                <a href="{{ $url }}" class="rc-btn {{ $loop->first ? 'rc-btn-primary' : 'rc-btn-outline' }}">{{ $label }}</a>
            @endforeach
        </div>
    @endif
</header>

<nav class="rc-tabs" aria-label="Live Cafe admin">
    <a href="{{ route('running.manage.index') }}" class="rc-tab {{ $active === 'events' ? 'is-active' : '' }}">Runs &amp; events</a>
    <a href="{{ route('running.manage.announcements.index') }}" class="rc-tab {{ $active === 'announcements' ? 'is-active' : '' }}">Announcements</a>
    <a href="{{ route('running.index') }}" class="rc-tab rc-tab-public">View the public page &rarr;</a>
</nav>

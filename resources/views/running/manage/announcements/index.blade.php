@extends('layouts.app')

@section('title', 'Announcements — Run club admin')

@push('styles')
    @include('running.partials.styles')
    @include('running.manage.partials.styles')
@endpush

@section('content')

<div class="rc-wrap">
    @include('running.manage.partials.header', [
        'title' => 'Announcements',
        'active' => 'announcements',
        'actionUrl' => route('running.manage.announcements.create'),
        'actionLabel' => 'Write an announcement',
    ])

    <p class="rc-muted rc-manage-intro">
        The newest published announcement shows at the top of the run club page. A pinned one stays there until you unpin it.
    </p>

    @if ($announcements->isEmpty())
        <p class="rc-empty">No announcements yet. <a href="{{ route('running.manage.announcements.create') }}" class="rc-link">Write the first one</a></p>
    @else
        <div class="rc-table-wrap">
            <table class="rc-table">
                <thead>
                    <tr>
                        <th scope="col">Announcement</th>
                        <th scope="col">Status</th>
                        <th scope="col">Written by</th>
                        <th scope="col"><span class="rc-sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($announcements as $announcement)
                        <tr>
                            <td>
                                <strong>{{ $announcement->title }}</strong>
                                <span class="rc-sub">{{ \Illuminate\Support\Str::limit($announcement->description, 110) }}</span>
                            </td>
                            <td>
                                <div class="rc-row-badges">
                                    @if ($announcement->isPublished())
                                        <span class="rc-badge rc-badge-going">Published</span>
                                    @else
                                        <span class="rc-badge rc-badge-draft">Draft</span>
                                    @endif
                                    @if ($announcement->is_pinned)
                                        <span class="rc-badge rc-badge-pinned">Pinned</span>
                                    @endif
                                </div>
                                @if ($announcement->published_at)
                                    <span class="rc-sub">{{ $announcement->published_at->format('j M Y') }}</span>
                                @endif
                            </td>
                            <td>{{ $announcement->author ? $announcement->author->name.' '.$announcement->author->surname : '—' }}</td>
                            <td>
                                <div class="rc-actions">
                                    @if ($announcement->isPublished())
                                        <a href="{{ route('running.announcements.show', $announcement) }}">View</a>
                                    @endif
                                    <a href="{{ route('running.manage.announcements.edit', $announcement) }}">Edit</a>
                                    <form method="POST" action="{{ route('running.manage.announcements.destroy', $announcement) }}"
                                          onsubmit="return confirm('Delete this announcement? This can\'t be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rc-btn-text is-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection

<?php

namespace App\Http\Controllers\Running\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Running\AnnouncementRequest;
use App\Models\Announcement;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * Where admins write announcements. They can be about anything at Live Cafe,
 * and can link to a run or event. "Save as draft" keeps one private;
 * "Publish" puts it on the run club and announcements pages.
 */
class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::query()
            ->with(['author', 'event'])
            ->orderByDesc('is_pinned')
            ->latest()
            ->get();

        return view('running.manage.announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        Gate::authorize('create', Announcement::class);

        $announcement = new Announcement;

        return view('running.manage.announcements.form', [
            'announcement' => $announcement,
            'events' => $this->linkableEvents($announcement),
        ]);
    }

    public function store(AnnouncementRequest $request): RedirectResponse
    {
        Gate::authorize('create', Announcement::class);

        $announcement = new Announcement($request->announcementAttributes());
        $announcement->user_id = $request->user()?->id;
        $announcement->published_at = $request->publishing() ? now()->toImmutable() : null;
        $announcement->save();

        return redirect()
            ->route('running.manage.announcements.index')
            ->with('success', $request->publishing() ? 'Announcement published.' : 'Announcement saved as a draft.');
    }

    public function edit(Announcement $announcement): View
    {
        Gate::authorize('update', $announcement);

        return view('running.manage.announcements.form', [
            'announcement' => $announcement,
            'events' => $this->linkableEvents($announcement),
        ]);
    }

    public function update(AnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        Gate::authorize('update', $announcement);

        $announcement->fill($request->announcementAttributes());

        if (! $request->publishing()) {
            $announcement->published_at = null;
        } elseif ($announcement->published_at === null) {
            // Keep the original date when an already-published notice is edited.
            $announcement->published_at = now()->toImmutable();
        }

        $announcement->save();

        return redirect()
            ->route('running.manage.announcements.index')
            ->with('success', $request->publishing() ? 'Announcement saved and published.' : 'Announcement saved as a draft.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        Gate::authorize('delete', $announcement);

        $announcement->delete();

        return redirect()
            ->route('running.manage.announcements.index')
            ->with('success', 'Announcement deleted.');
    }

    /**
     * The runs and events an announcement can link to: everything upcoming,
     * plus the one it already links to if that has since taken place.
     *
     * @return Collection<int, Event>
     */
    private function linkableEvents(Announcement $announcement): Collection
    {
        $events = Event::query()->upcoming()->chronological()->get();

        if ($announcement->event_id !== null && ! $events->contains('id', $announcement->event_id)) {
            $linked = Event::query()->find($announcement->event_id);

            if ($linked !== null) {
                $events->prepend($linked);
            }
        }

        return $events;
    }
}

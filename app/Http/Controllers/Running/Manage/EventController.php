<?php

namespace App\Http\Controllers\Running\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Running\EventRequest;
use App\Models\Event;
use App\Models\Rsvp;
use App\Services\RunningClub\RunningClubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Where admins add and manage runs and events. It lives under /running/manage
 * so the admin dashboard can link to it without either module editing the other.
 */
class EventController extends Controller
{
    public function __construct(
        private readonly RunningClubService $runningClub,
    ) {}

    public function index(): View
    {
        // rsvps_count includes cancelled RSVPs (it decides whether Delete is
        // offered); members_going counts only the people still going.
        $counts = [
            'rsvps',
            'rsvps as members_going' => fn ($query) => $query->where('status', Rsvp::STATUS_GOING),
        ];

        $upcoming = Event::query()
            ->upcoming()
            ->chronological()
            ->withCount($counts)
            ->get();

        $past = Event::query()
            ->past()
            ->orderByDesc('event_date')
            ->orderByDesc('event_time')
            ->withCount($counts)
            ->limit(20)
            ->get();

        return view('running.manage.events.index', compact('upcoming', 'past'));
    }

    /**
     * The add form. "Duplicate" links here with ?from={id} to pre-fill the
     * form with that run's details a week later; nothing is saved until the
     * admin presses Save.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', Event::class);

        $event = new Event;
        $source = $request->filled('from') ? Event::query()->find($request->integer('from')) : null;

        if ($source !== null) {
            $event->fill($source->only(['title', 'type', 'address', 'distance_km', 'pace', 'dress_code', 'sponsor', 'description']));
            $event->event_date = $source->event_date->addWeek();
            $event->event_time = $source->event_time;
        }

        return view('running.manage.events.form', compact('event', 'source'));
    }

    public function store(EventRequest $request): RedirectResponse
    {
        Gate::authorize('create', Event::class);

        $event = Event::query()->create($request->eventAttributes());
        $this->savePhoto($request, $event);

        return redirect()
            ->route('running.manage.index')
            ->with('success', "Added {$event->title} on {$event->event_date->format('D j M')}.");
    }

    public function edit(Event $event): View
    {
        Gate::authorize('update', $event);

        // The count lets the form warn that a change will email people.
        $event->load('photo')->loadCount('activeRsvps');

        return view('running.manage.events.form', ['event' => $event, 'source' => null]);
    }

    public function update(EventRequest $request, Event $event): RedirectResponse
    {
        Gate::authorize('update', $event);

        $this->runningClub->updateEvent($event, $request->eventAttributes());
        $this->savePhoto($request, $event);

        return redirect()
            ->route('running.manage.index')
            ->with('success', "Saved {$event->title}.");
    }

    public function cancel(Event $event): RedirectResponse
    {
        Gate::authorize('update', $event);

        $this->runningClub->cancelEvent($event);

        return redirect()
            ->route('running.manage.index')
            ->with('success', "Cancelled {$event->title}. Everyone who was going has been emailed.");
    }

    /**
     * Deleting is only for runs nobody has RSVP'd to (a mistake, say).
     * Runs with RSVPs are cancelled instead, which keeps their RSVP records.
     */
    public function destroy(Event $event): RedirectResponse
    {
        Gate::authorize('delete', $event);

        if ($event->rsvps()->exists()) {
            return redirect()
                ->route('running.manage.index')
                ->with('error', "{$event->title} has RSVPs, so it can be cancelled but not deleted. That keeps the RSVP records.");
        }

        $this->deletePhoto($event);
        $event->delete();

        return redirect()
            ->route('running.manage.index')
            ->with('success', "Deleted {$event->title}.");
    }

    /**
     * Who's going and the expected headcount. Admins only.
     */
    public function rsvps(Event $event): View
    {
        Gate::authorize('update', $event);

        $rsvps = $event->rsvps()->with('user')->oldest()->get();

        $going = $rsvps->filter(fn (Rsvp $rsvp) => $rsvp->isGoing())->values();
        $cancelled = $rsvps->reject(fn (Rsvp $rsvp) => $rsvp->isGoing())->values();

        return view('running.manage.events.rsvps', compact('event', 'going', 'cancelled'));
    }

    public function export(Event $event): StreamedResponse
    {
        Gate::authorize('update', $event);

        $filename = Str::slug($event->title.' '.$event->event_date->format('Y-m-d')).'-rsvps.csv';

        return response()->streamDownload(function () use ($event) {
            $out = fopen('php://output', 'w');

            if ($out === false) {
                return;
            }

            fputcsv($out, ['Name', 'Surname', 'Email', 'Phone', ucfirst($event->extrasNoun()), 'Headcount', 'Status', 'RSVP date'], ',', '"', '');

            foreach ($event->rsvps()->with('user')->oldest()->get() as $rsvp) {
                $row = [
                    $rsvp->user->name,
                    $rsvp->user->surname,
                    $rsvp->user->email,
                    $rsvp->user->phone_number,
                    (string) $rsvp->extras,
                    $rsvp->isGoing() ? (string) $rsvp->headcount() : '0',
                    $rsvp->isGoing() ? 'Going' : 'Cancelled',
                    $rsvp->created_at?->format('Y-m-d H:i') ?? '',
                ];

                fputcsv($out, array_map($this->csvSafe(...), $row), ',', '"', '');
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Spreadsheet apps run a cell that starts with = + - or @ as a formula.
     * Prefixing a quote means a name like "=HYPERLINK(...)" typed at sign-up
     * stays plain text when the admin opens the export.
     */
    private function csvSafe(string $value): string
    {
        return preg_match('/^[=+\-@\t\r]/', $value) === 1 ? "'".$value : $value;
    }

    private function savePhoto(EventRequest $request, Event $event): void
    {
        $file = $request->file('photo');

        if ($request->boolean('remove_photo') || $file instanceof UploadedFile) {
            $this->deletePhoto($event);
        }

        if (! $file instanceof UploadedFile) {
            return;
        }

        $path = $file->store('events', 'public');

        if ($path === false) {
            return;
        }

        $event->photo()->create([
            'path' => $path,
            'alt_text' => $event->title,
            'sort_order' => 0,
        ]);
    }

    private function deletePhoto(Event $event): void
    {
        $photo = $event->photo;

        if ($photo !== null) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }

        $event->unsetRelation('photo');
    }
}

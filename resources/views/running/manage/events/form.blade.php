@extends('layouts.app')

@php
    $editing = $event->exists;
    $pageTitle = $editing ? 'Edit '.$event->title : 'Add a run or event';
    $type = old('type', $event->type);
    $startTime = old('event_time', $event->event_time ? substr($event->event_time, 0, 5) : null);
    $hasPhoto = $editing && $event->photo !== null;
@endphp

@section('title', $pageTitle.' — Run club admin')

@push('styles')
    @include('running.partials.styles')
    @include('running.manage.partials.styles')
@endpush

@section('content')

<div class="rc-wrap">
    @include('running.manage.partials.header', ['title' => $pageTitle, 'active' => 'events'])

    @if ($source)
        <p class="rc-closing-note">
            Copied from {{ $source->title }} on {{ $source->event_date->format('D j M') }} and moved a week later.
            Check the details and save. The photo isn't copied.
        </p>
    @endif

    @if ($editing && $event->isCancelled())
        <p class="rc-error-summary">This {{ $event->isRun() ? 'run' : 'event' }} was cancelled, so changes won't be emailed to anyone.</p>
    @elseif ($editing && $event->active_rsvps_count > 0)
        <p class="rc-closing-note">
            {{ $event->active_rsvps_count }} {{ \Illuminate\Support\Str::plural('member', $event->active_rsvps_count) }}
            {{ $event->active_rsvps_count === 1 ? 'is' : 'are' }} going. Changing the date, start time or location emails them the new details.
        </p>
    @endif

    @if ($errors->any())
        <div class="rc-error-summary" role="alert">
            Please fix the {{ \Illuminate\Support\Str::plural('field', $errors->count()) }} marked below.
        </div>
    @endif

    <form method="POST"
          action="{{ $editing ? route('running.manage.events.update', $event) : route('running.manage.events.store') }}"
          enctype="multipart/form-data"
          class="rc-form">
        @csrf
        @if ($editing)
            @method('PUT')
        @endif

        <div class="rc-form-grid">
            <div class="rc-field rc-field-wide">
                <fieldset>
                    <legend>Type</legend>
                    <label class="rc-choice">
                        <input type="radio" name="type" value="{{ \App\Models\Event::TYPE_RUN }}" @checked($type === \App\Models\Event::TYPE_RUN)>
                        Run
                    </label>
                    <label class="rc-choice">
                        <input type="radio" name="type" value="{{ \App\Models\Event::TYPE_EVENT }}" @checked($type === \App\Models\Event::TYPE_EVENT)>
                        Event
                    </label>
                </fieldset>
                <span class="rc-hint">Use Event for socials, brand activations and anything that isn't a club run.</span>
                @error('type') <p class="rc-error">{{ $message }}</p> @enderror
            </div>

            <div class="rc-field rc-field-wide">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" maxlength="120" required
                       placeholder="Saturday Run" @error('title') aria-invalid="true" @enderror>
                @error('title') <p class="rc-error">{{ $message }}</p> @enderror
            </div>

            <div class="rc-field">
                <label for="event_date">Date</label>
                <input type="date" id="event_date" name="event_date"
                       value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}"
                       @unless ($editing) min="{{ now()->format('Y-m-d') }}" @endunless
                       required @error('event_date') aria-invalid="true" @enderror>
                @error('event_date') <p class="rc-error">{{ $message }}</p> @enderror
            </div>

            <div class="rc-field">
                <label for="event_time">Start time</label>
                <input type="time" id="event_time" name="event_time" value="{{ $startTime }}" required
                       @error('event_time') aria-invalid="true" @enderror>
                @error('event_time') <p class="rc-error">{{ $message }}</p> @enderror
            </div>

            <div class="rc-field rc-field-wide">
                <label for="address">Location</label>
                <input type="text" id="address" name="address" value="{{ old('address', $event->address) }}" maxlength="255" required
                       placeholder="102 Rivonia Road, Sandton" @error('address') aria-invalid="true" @enderror>
                <span class="rc-hint">Members get a Google Maps link to this address.</span>
                @error('address') <p class="rc-error">{{ $message }}</p> @enderror
            </div>

            <div class="rc-field">
                <label for="distance_km">Distance in km <span class="rc-optional" data-distance-optional @if ($type === \App\Models\Event::TYPE_RUN) hidden @endif>(optional)</span></label>
                <input type="number" id="distance_km" name="distance_km" value="{{ old('distance_km', $event->distance_km) }}"
                       min="0.1" max="200" step="0.1" inputmode="decimal" placeholder="5"
                       @required($type === \App\Models\Event::TYPE_RUN) @error('distance_km') aria-invalid="true" @enderror>
                @error('distance_km') <p class="rc-error">{{ $message }}</p> @enderror
            </div>

            <div class="rc-field">
                <label for="pace">Pace <span class="rc-optional">(optional)</span></label>
                <input type="text" id="pace" name="pace" value="{{ old('pace', $event->pace) }}" maxlength="40" placeholder="Easy">
                @error('pace') <p class="rc-error">{{ $message }}</p> @enderror
            </div>

            <div class="rc-field">
                <label for="dress_code">Dress code <span class="rc-optional">(optional)</span></label>
                <input type="text" id="dress_code" name="dress_code" value="{{ old('dress_code', $event->dress_code) }}" maxlength="120" placeholder="Black and pink">
                @error('dress_code') <p class="rc-error">{{ $message }}</p> @enderror
            </div>

            <div class="rc-field">
                <label for="sponsor">Presented by <span class="rc-optional">(optional)</span></label>
                <input type="text" id="sponsor" name="sponsor" value="{{ old('sponsor', $event->sponsor) }}" maxlength="120" placeholder="Brand name">
                <span class="rc-hint">For brand-sponsored runs and events. It shows as a badge.</span>
                @error('sponsor') <p class="rc-error">{{ $message }}</p> @enderror
            </div>

            <div class="rc-field rc-field-wide">
                <label for="description">Description <span class="rc-optional">(optional)</span></label>
                <textarea id="description" name="description" maxlength="5000"
                          placeholder="The route, where to meet, what to bring.">{{ old('description', $event->description) }}</textarea>
                @error('description') <p class="rc-error">{{ $message }}</p> @enderror
            </div>

            <div class="rc-field rc-field-wide">
                <label for="photo">Photo <span class="rc-optional">(optional)</span></label>
                @if ($hasPhoto)
                    <img src="{{ $event->photoUrl() }}" alt="Current photo" class="rc-photo-preview">
                    <label class="rc-choice">
                        <input type="checkbox" name="remove_photo" value="1" @checked(old('remove_photo'))>
                        Remove this photo
                    </label>
                @endif
                <input type="file" id="photo" name="photo" accept="image/*">
                <span class="rc-hint">
                    {{ $hasPhoto ? 'Choose a file to replace it.' : 'Without one, a club photo is used.' }} JPG, PNG or WebP up to 5 MB.
                </span>
                @error('photo') <p class="rc-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <p class="rc-closing-note" id="closing-note" data-cutoff-hours="{{ \App\Models\Event::RSVP_CUTOFF_HOURS }}" aria-live="polite">
            @if ($event->event_date && $event->event_time)
                RSVPs close {{ $event->rsvpClosesAt()->format('D j M') }} at {{ $event->rsvpClosesAt()->format('H:i') }}, {{ \App\Models\Event::RSVP_CUTOFF_HOURS }} hours before the start.
            @else
                Pick a date and start time to see when RSVPs close.
            @endif
        </p>

        <div class="rc-form-buttons">
            <button type="submit" class="rc-btn rc-btn-primary">{{ $editing ? 'Save changes' : 'Add to the calendar' }}</button>
            <a href="{{ route('running.manage.index') }}" class="rc-link">Back to runs &amp; events</a>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    (() => {
        const form = document.querySelector('.rc-form');
        const note = document.getElementById('closing-note');
        const date = document.getElementById('event_date');
        const time = document.getElementById('event_time');
        const distance = document.getElementById('distance_km');
        const distanceOptional = document.querySelector('[data-distance-optional]');

        if (!form || !note || !date || !time || !distance || !distanceOptional) {
            return;
        }

        // Distance is required for a run and optional for an event.
        const syncType = () => {
            const isRun = form.querySelector('input[name="type"]:checked')?.value === 'run';
            distance.required = isRun;
            distanceOptional.hidden = isRun;
        };

        const hours = Number(note.dataset.cutoffHours);
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const pad = (n) => String(n).padStart(2, '0');

        // Shows when RSVPs will close as the admin picks a date and time.
        // The sum is plain clock arithmetic done in UTC, so the admin's own
        // timezone or daylight saving can't shift it (the club runs on SAST).
        const syncClosingNote = () => {
            const [y, m, d] = date.value.split('-').map(Number);
            const [hh, mm] = time.value.split(':').map(Number);

            if (!y || !m || !d || time.value === '' || Number.isNaN(hh) || Number.isNaN(mm)) {
                note.textContent = 'Pick a date and start time to see when RSVPs close.';
                return;
            }

            const closes = new Date(Date.UTC(y, m - 1, d, hh, mm) - hours * 60 * 60 * 1000);
            note.textContent = `RSVPs close ${days[closes.getUTCDay()]} ${closes.getUTCDate()} ${months[closes.getUTCMonth()]}`
                + ` at ${pad(closes.getUTCHours())}:${pad(closes.getUTCMinutes())}, ${hours} hours before the start.`;
        };

        form.querySelectorAll('input[name="type"]').forEach((radio) => radio.addEventListener('change', syncType));
        date.addEventListener('input', syncClosingNote);
        time.addEventListener('input', syncClosingNote);

        syncType();
        syncClosingNote();
    })();
</script>
@endpush

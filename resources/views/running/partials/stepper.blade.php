{{--
    The − / + control for how many people a member brings. Expects $value, and
    optionally $max (the most allowed) and $extraNoun ("extra runner" or "guest").
--}}
@php($extraNoun ??= 'extra runner')
<div class="rc-stepper" data-stepper>
    <button type="button" data-step="-1" aria-label="One fewer {{ $extraNoun }}">&minus;</button>
    <input type="number"
           id="extras"
           name="extras"
           value="{{ old('extras', $value) }}"
           min="0"
           max="{{ $max ?? 99 }}"
           inputmode="numeric"
           aria-describedby="extras-hint">
    <button type="button" data-step="1" aria-label="One more {{ $extraNoun }}">+</button>
</div>

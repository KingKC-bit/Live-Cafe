{{-- Extra runners control. Expects $value, and optionally $max (the most allowed). --}}
<div class="rc-stepper" data-stepper>
    <button type="button" data-step="-1" aria-label="One fewer extra runner">&minus;</button>
    <input type="number"
           id="extras"
           name="extras"
           value="{{ old('extras', $value) }}"
           min="0"
           max="{{ $max ?? 99 }}"
           inputmode="numeric"
           aria-describedby="extras-hint">
    <button type="button" data-step="1" aria-label="One more extra runner">+</button>
</div>

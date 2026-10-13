<?php

namespace App\Http\Requests\Running;

use App\Models\Event;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validates the admin form for adding or editing a run or event.
 */
class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $creating = $this->isMethod('post');

        return [
            'type' => ['required', Rule::in([Event::TYPE_RUN, Event::TYPE_EVENT])],
            'title' => ['required', 'string', 'max:120'],
            // A new run can't start in the past. Editing an old run for the
            // records is still allowed.
            'event_date' => array_filter(['required', 'date_format:Y-m-d', $creating ? 'after_or_equal:today' : null]),
            'event_time' => ['required', 'date_format:H:i'],
            'address' => ['required', 'string', 'max:255'],
            // A run needs a distance. An event can be anything (a tasting, a
            // launch), so it has no distance or pace: the form hides those
            // fields and anything sent for them is ignored, not validated.
            'distance_km' => ['exclude_if:type,'.Event::TYPE_EVENT, 'nullable', 'required_if:type,'.Event::TYPE_RUN, 'numeric', 'min:0.1', 'max:200'],
            'pace' => ['exclude_if:type,'.Event::TYPE_EVENT, 'nullable', 'string', 'max:40'],
            'dress_code' => ['nullable', 'string', 'max:120'],
            'sponsor' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'remove_photo' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'event_date.after_or_equal' => 'The date must be today or later.',
            'distance_km.required_if' => 'Add the distance for a run.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'event_date' => 'date',
            'event_time' => 'start time',
            'address' => 'location',
            'distance_km' => 'distance',
        ];
    }

    /**
     * The validated fields that belong on the events table (the photo is
     * handled separately). An event's distance and pace are cleared, so a
     * run that's changed into an event doesn't keep them.
     *
     * @return array<string, mixed>
     */
    public function eventAttributes(): array
    {
        $attributes = $this->safe()->except(['photo', 'remove_photo']);

        if ($attributes['type'] === Event::TYPE_EVENT) {
            $attributes['distance_km'] = null;
            $attributes['pace'] = null;
        }

        return $attributes;
    }
}

<?php

namespace App\Http\Requests\Running;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnnouncementRequest extends FormRequest
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
        return [
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:5000'],
            'is_pinned' => ['nullable', 'boolean'],
            // Optional: the run or event the announcement is about.
            'event_id' => ['nullable', 'integer', Rule::exists('events', 'id')],
            // Which button was pressed: "Publish" or "Save as draft".
            'action' => ['required', 'in:publish,draft'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'description' => 'message',
            'event_id' => 'run or event',
        ];
    }

    public function publishing(): bool
    {
        return $this->input('action') === 'publish';
    }

    /**
     * @return array{title: string, description: string, is_pinned: bool, event_id: int|null}
     */
    public function announcementAttributes(): array
    {
        return [
            'title' => (string) $this->validated('title'),
            'description' => (string) $this->validated('description'),
            'is_pinned' => $this->boolean('is_pinned'),
            'event_id' => $this->filled('event_id') ? $this->integer('event_id') : null,
        ];
    }
}

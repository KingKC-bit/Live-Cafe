<?php

namespace App\Http\Requests\Running;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
        ];
    }

    public function publishing(): bool
    {
        return $this->input('action') === 'publish';
    }

    /**
     * @return array{title: string, description: string, is_pinned: bool}
     */
    public function announcementAttributes(): array
    {
        return [
            'title' => (string) $this->validated('title'),
            'description' => (string) $this->validated('description'),
            'is_pinned' => $this->boolean('is_pinned'),
        ];
    }
}

<?php

namespace App\Http\Requests\Running;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RsvpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * There's no business limit on extra runners. The page only lets people
     * change the number with − and + buttons; the max here just rejects a
     * tampered form that would wreck the organisers' estimate.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'extras' => ['required', 'integer', 'min:0', 'max:99'],
        ];
    }

    public function extras(): int
    {
        return $this->integer('extras');
    }
}

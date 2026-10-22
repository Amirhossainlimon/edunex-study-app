<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudySessionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:600'],
            'date' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}

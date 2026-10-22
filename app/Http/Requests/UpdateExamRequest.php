<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:120'],
            'exam_date' => ['required', 'date'],
        ];
    }
}

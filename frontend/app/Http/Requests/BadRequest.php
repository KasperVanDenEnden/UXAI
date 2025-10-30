<?php

namespace App\Http\Requests;

use App\Enums\YesNoEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'anemia' => ['required', 'string', Rule::enum(YesNoEnum::class)],
            'stress' => ['required', 'string', Rule::enum(YesNoEnum::class)],
            'chronic_illness' => ['required', 'string', Rule::enum(YesNoEnum::class)],
            'family_illness' => ['required', 'string', Rule::enum(YesNoEnum::class)],
        ];
    }
}

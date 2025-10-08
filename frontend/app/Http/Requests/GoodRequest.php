<?php

namespace App\Http\Requests;

use App\YesNoEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GoodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'anemia' => ['required', 'string', Rule::enum(YesNoEnum::class)],
            'stress' => ['required', 'string', Rule::enum(YesNoEnum::class)],
            'chronic_illness' => ['required', 'string', Rule::enum(YesNoEnum::class)],
            'family_illness' => ['required', 'string', Rule::enum(YesNoEnum::class)],
        ];
    }
}

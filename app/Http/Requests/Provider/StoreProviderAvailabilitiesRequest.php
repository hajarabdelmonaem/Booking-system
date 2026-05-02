<?php

namespace App\Http\Requests\Provider;

use Illuminate\Foundation\Http\FormRequest;

class StoreProviderAvailabilitiesRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'availabilities' => ['required', 'array', 'min:1'],
            'availabilities.*.day' => ['required', 'string', 'in:0,1,2,3,4,5,6'],
            'availabilities.*.start_at' => ['required', 'string'],
            'availabilities.*.end_at' => ['required', 'string'],
        ];
    }
}

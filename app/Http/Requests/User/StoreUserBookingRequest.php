<?php

namespace App\Http\Requests\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserBookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'service_id' => [
                'required',
                'integer',
                'exists:services,id',
            ],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'start_at' => ['required', 'string'],
        ];
    }
}

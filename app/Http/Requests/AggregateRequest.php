<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AggregateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'symbol' => ['required', 'string', 'max:10'],
            'multiplier' => ['required', 'integer', 'min:1'],
            'timespan' => ['required', 'string', 'in:minute,hour,day,week,month'],
            'startDate' => ['required', 'date', 'date_format:Y-m-d'],
            'endDate' => ['required', 'date', 'date_format:Y-m-d'],
        ];
    }
}

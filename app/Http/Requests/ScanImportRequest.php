<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ScanImportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'mimes:csv', 'max:1024',
                function ($attribute, $value, $fail) {
                    if (! preg_match('/^\d{8}_Momo\.csv$/', $value->getClientOriginalName())) {
                        $fail('file.name');
                    }
                }, ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'file.required',
            'file.mimetypes' => 'file.type',
            'file.max' => 'file.size',
        ];
    }
}

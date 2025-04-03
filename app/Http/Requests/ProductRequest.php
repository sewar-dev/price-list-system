<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'country_code' => [
                'nullable',
                'string',
                'size:2',
                Rule::exists('countries', 'code')
            ],
            'currency_code' => [
                'nullable',
                'string',
                'size:3',
                Rule::exists('currencies', 'code')
            ],
            'date' => [
                'nullable',
                'date_format:Y-m-d',
                'before_or_equal:today'
            ],
            'order' => [
                'nullable',
                Rule::in(['lowest-to-highest', 'highest-to-lowest'])
            ]
        ];
    }


    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'country_code' => 'country code',
            'currency_code' => 'currency code',
            'date' => 'effective date'
        ];
    }

    public function getData(): array
    {
        return [
            'country_code'   => $this->input('country_code') ?? '',
            'currency_code'  => $this->input('currency_code') ?? '',
            'date'           => $this->input('date') ?? '',
            'order'          => $this->input('order') ?? ''
        ];
    }


    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            sendResponse(false,$validator->errors()->first(), null, 422)
        );
    }
}

<?php

namespace App\Http\Requests\Backend\TicketingModule;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class TicketCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Set to true to allow authorization
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'brands.*' => [
                'required',
                Rule::exists('brands', 'id'),
            ],

            'service_center.*' => [
                'required',
                Rule::exists('service_centers', 'id'),
            ],
            'business_units.*' => [
                'required',
                Rule::exists('business_units', 'id')
            ],
            'product_category.*' => [
                'required',
                Rule::exists('product_categories', 'id')
            ],
            'category_problem.*' => [
                'required',
                Rule::exists('product_category_problems', 'id')
            ],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'address' => ['required'],
            'district_id' => ['required', Rule::exists('districts', 'id')],
            'thana_id' => ['required', Rule::exists('thanas', 'id')]
            // Add other validation rules for your other fields if needed
        ];
    }

    /**
     * @throws HttpResponseException
     */
    function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}

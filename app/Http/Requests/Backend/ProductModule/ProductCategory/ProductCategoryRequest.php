<?php

namespace App\Http\Requests\Backend\ProductModule\ProductCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules=[
            "name" =>'required|unique:product_categories,name',
            "is_active"=>'required',
            "brand_id" => [
                'required',
                'array',
                Rule::exists('brands', 'id'),
                'min:1', // Ensure at least one element in the array
            ],
            "problem_en"=>'array',
            "problem_bn"=>'array'
        ];

        if ($this->has('problem_en') && $this->has('problem_bn')) {
            $rules['problem_en'] = 'size:' . count($this->input('problem_bn'));
            $rules['problem_bn'] = 'size:' . count($this->input('problem_en'));
//            $rules['problem_en.*'] = ['string',
//                Rule::unique('product_category_problems','name')->where(function ($query){
//                    return $query->where('lang_code','en');
//                })
//            ];
//            $rules['problem_bn.*'] = ['string',
//                Rule::unique('product_category_problems','name')->where(function ($query){
//                    return $query->where('lang_code','bn');
//                })
//            ];

        }

        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $id = $this->route()->parameter('id');
            $rules['name'] .= ',' . $id;
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'problem_en.*.string' => 'Each item in the problem_en array must be a string.',
            'problem_bn.*.string' => 'Each item in the problem_bn array must be a string.',
            'problem_en.size' => 'The number of items in the problem_en must match the number of items in the problem_bn.',
            'problem_bn.size' => 'The number of items in the problem_bn must match the number of items in the problem_en.',
        ];
    }
}

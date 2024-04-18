<?php

namespace App\Http\Requests\Backend\ProductModule\ProductCategory\Category_Problem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCategoryCreateProblemRequest extends FormRequest
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
        $rules = [
            'pc_id' => 'required|exists:product_categories,id',
            'is_active'=>'required',
            "problem_en" => 'array|required',
            "problem_bn" => 'array|required'
        ];
        $pc_id = $this->input('pc_id');
        if ($this->has('problem_en') && $this->has('problem_bn')) {
            $rules['problem_en'] = 'size:' . count($this->input('problem_bn'));
            $rules['problem_bn'] = 'size:' . count($this->input('problem_en'));
            $rules['problem_en.*'] = ['string',
                Rule::unique('product_category_problems', 'name')->where(function ($query) use ($pc_id) {
                    return $query->where('product_category_id', $pc_id);
                })
            ];
            $rules['problem_bn.*'] = ['string',
                Rule::unique('product_category_problems', 'name')->where(function ($query) use ($pc_id) {
                    return $query->where('product_category_id', $pc_id);
                })
            ];
        }
        return $rules;

    }

    public function messages(): array
    {
        return [
            'problem_en.size' => 'The number of items in the problem_en must match the number of items in the problem_bn.',
            'problem_bn.size' => 'The number of items in the problem_bn must match the number of items in the problem_en.',
            'problem_en.*.unique' => 'The :attribute has already been taken for this product category.',
            'problem_bn.*.unique' => 'The :attribute has already been taken for this product category.',
        ];
    }
}

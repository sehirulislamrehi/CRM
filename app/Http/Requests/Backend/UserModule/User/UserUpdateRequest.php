<?php

namespace App\Http\Requests\Backend\UserModule\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $userId = $this->route('id'); // Assuming your route parameter is named 'id'

        return [
            'username' => [
                'required',
                Rule::unique('users')->ignore($userId),
            ],
            'phone'=>'required',
            'fullname' => 'required',
            'is_active' => 'required',
            // 'password' => 'required',
            'user_group_id' => 'required|exists:user_groups,id',
            "service_center_id" => [
                'sometimes',
                'required',
                'array',
                Rule::exists('service_centers', 'id'),
                'min:1', // Ensure at least one element in the array
            ],
            "business_unit_id" => [
                'sometimes',
                'required',
                'array',
                Rule::exists('business_units', 'id'),
                'min:1', // Ensure at least one element in the array
            ],
        ];
    }
}

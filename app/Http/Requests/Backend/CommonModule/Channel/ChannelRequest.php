<?php

namespace App\Http\Requests\Backend\CommonModule\Channel;

use Illuminate\Foundation\Http\FormRequest;

class ChannelRequest extends FormRequest
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
            'name' => 'required',
            'channel_type' => 'required',
            'channel_number' => 'required',
            'logo' => 'sometimes|nullable|file|mimes:png,jpg,jpeg',
            'is_active' => 'required',
        ];

        // For update, add unique validation rules with ignore
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $id = $this->route()->parameter('id');
            $rules['name'] .= '|unique:channels,name,' . $id;
            $rules['channel_type'] .= '|unique:channels,channel_type,' . $id;
            $rules['channel_number'] .= '|unique:channels,channel_number,' . $id;
        }

        return $rules;
    }
}

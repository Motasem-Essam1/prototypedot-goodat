<?php

namespace App\Http\Requests\Dashboard\Packages;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddPackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'package_name' => 'required|string|min:3|max:25',
            'number_of_services' => 'required|numeric|min:0',
            'number_of_images_per_service' => 'required|numeric|min:0',
            'search_package_priority' => Rule::in(['High','Normal']),
            'tasks_notification_criteria' => Rule::in([1,0]),
            'has_price' => Rule::in([1,0]),
            'has_condition' => Rule::in([1,0]),
            'is_public' => Rule::in([1,0]),
            'phone_status' => Rule::in([1,0]),
            'per_month' => Rule::in([1,0]),
            'price' => 'required|min:0',
            'description' => 'nullable|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'color' => 'nullable|string',
            'slug' => 'required|string'
        ];
    }
}

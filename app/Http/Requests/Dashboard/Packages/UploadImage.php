<?php

namespace App\Http\Requests\Dashboard\Packages;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadImage extends FormRequest
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
            'package_id' => ['required', Rule::exists('packages', 'id')],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}

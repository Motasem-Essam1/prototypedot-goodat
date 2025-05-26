<?php

namespace App\Http\Requests\Dashboard\SubCategories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddSubCategoryRequest extends FormRequest
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
            'category_id' => ['required',Rule::exists('categories','id')],
            'sub_category_name' => 'required|string|unique:sub_categories,sub_category_name',
            'is_active' => Rule::in([1,0]),
        ];
    }
}

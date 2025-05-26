<?php

namespace App\Http\Requests\Dashboard\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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

            'phone' =>  ['required', Rule::unique('users', 'phone_number')->ignore($this->user()->id, 'id')],
            'email' =>  [Rule::unique('users', 'email')->ignore($this->user()->id, 'id')],
            'category_id' =>  'required'
        ];
    }


    public function messages()
    {
        return [
            'phone.unique' => 'The phone has already been taken',
            'email.unique' => 'The Email has already been taken',
            'category_id.required' =>  'Category field is required',
        ];
    }

}

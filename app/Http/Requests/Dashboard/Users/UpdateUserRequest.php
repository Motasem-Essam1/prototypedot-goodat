<?php

namespace App\Http\Requests\Dashboard\Users;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;

class UpdateUserRequest extends FormRequest
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
            'fullname'    => 'required|string|min:4|max:25',
            'email'       => "required|email|max:50|unique:users,email,$this->id,id",
            'phone_code'  => 'required',
            'phone'       => "required|unique:users,phone_number,$this->id,id"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'    => false,
            'message'      => 'invalid data',
            'data'   => Arr::flatten($validator->errors()->toArray()),
        ]));
    }
}

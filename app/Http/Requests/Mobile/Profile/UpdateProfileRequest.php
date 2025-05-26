<?php

namespace App\Http\Requests\Mobile\Profile;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
           // 'email'       => "required|email|max:50|unique:users,email,$this->id,id",
            'phone_code'  => 'required',
          //  'phone_number'=> "required|unique:users,email,$this->id,id",

            'phone_number' =>  ['required', Rule::unique('users', 'phone_number')->ignore($this->user()->id, 'id')],
            'email' =>  ['email', 'max:50', Rule::unique('users', 'email')->ignore($this->user()->id, 'id')],
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

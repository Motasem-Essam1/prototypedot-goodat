<?php

namespace App\Http\Requests\Dashboard\Services;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;

class AddServiceRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:sub_categories,id',
            'service_name' => 'required|string|unique:services,service_name',
            'service_description' => 'required|string',
            'starting_price' => 'required|string',
            'ending_price' => 'required|string|gt:starting_price',
            'location_lng' => 'required|string',
            'location_lat' => 'required|string',
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

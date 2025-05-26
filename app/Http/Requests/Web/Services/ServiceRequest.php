<?php

namespace App\Http\Requests\Web\Services;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;

class ServiceRequest extends FormRequest
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
            'category_id' => 'required|exists:sub_categories,id',
            'service_name' => 'required|string|unique:services',
            'service_description' => 'required|string',
            'starting_price' => 'required|string',
            'ending_price' => 'required|string|gt:starting_price',
            'location_lng' => 'required|string',
            'location_lat' => 'required|string',
            'images' => 'required'
         ];
    }
}

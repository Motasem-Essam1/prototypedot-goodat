<?php

namespace App\Http\Requests\Mobile\Services;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;

class UpdateTaskRequest extends FormRequest
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
            'id' => 'required|exists:tasks,id,deleted_at,NULL',
            'category_id' => 'required|exists:sub_categories,id',
            'task_name' =>  'required|string|unique:tasks,task_name,'.$this->id,
            'task_description' => 'required|string',
            'starting_price' => 'required',
            'ending_price' => 'required|string|gt:starting_price',
            'location_lng' => 'required|string',
            'location_lat' => 'required|string',
        ];
    }
}

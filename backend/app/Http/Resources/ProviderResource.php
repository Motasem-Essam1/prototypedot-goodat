<?php

namespace App\Http\Resources;

use App\Models\Package;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userData = $this->whenLoaded('user_data');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at ? date('Y-m-d H:i:s', strtotime($this->email_verified_at)) : null,
            'phone_number' => $this->phone_number,
            'phone_verify_at' => $this->phone_verify_at ? date('Y-m-d H:i:s', strtotime($this->phone_verify_at)) : null,
            'country_code' => $this->country_code,
            'phoned_Signed' => $this->phoned_Signed,
            'user_sub_categories_text' => $this->user_sub_categories_text,
            'rate' => $this->rate ? +$this->rate : 0,
            'customer_review_number' => $this->customer_review_number,
            'is_like' => $this->is_like,
            'likes_count' => +$this->likes_count,
            'phone_status' => $this->phone_status,
            'verify_code' => $userData ? $userData->verify_code : "",
            'user_type' => $userData ?  $userData->user_type : "",
            'provider_id' => $userData ?  $userData->provider_id : "",
            'provider_type' => $userData ? $userData->provider_type : "",
            'avatar' => $userData ?  asset($userData->avatar) : "",
            'generated_Code' => $userData ?  $userData->generated_Code : "",
            'number_of_invites' => $userData ?  $userData->number_of_invites : "",
            'nominated_by' => $userData ?  $userData->nominated_by : "",
            'package_id' => $userData ?  $userData->package_id : "",
            'package_name' =>  $userData ?  $userData->package_id ? Package::query()->find($this->whenLoaded('user_data')->package_id)->package_name : '' : "",
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}

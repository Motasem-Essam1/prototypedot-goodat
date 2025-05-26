<?php

namespace App\Http\Resources;

use App\Models\Package;
use App\Models\User;
use App\Models\UserSubCategories;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UpdateUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        ///get user sub categories
        $user_sub_categories = UserSubCategories::with('SubCategory')->where('user_id', auth('sanctum')->id())->get()->toArray();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at ? date('Y-m-d H:i:s', strtotime($this->email_verified_at)) : null,
            'phone_number' => $this->phone_number,
            'phone_verify_at' => $this->phone_verify_at ? date('Y-m-d H:i:s', strtotime($this->phone_verify_at)) : null,
            'country_code' => $this->country_code,
            'status' => $this->status,
            'is_like' => $this->is_like,
            'phone_status' => $this->phone_status,
            'verify_code' => $this->whenLoaded('user_data')->verify_code,
            'user_type' => $this->whenLoaded('user_data')->user_type,
            'provider_id' => +$this->whenLoaded('user_data')->provider_id,
            'provider_type' => $this->whenLoaded('user_data')->provider_type,
            'avatar' => asset($this->whenLoaded('user_data')->avatar),
            'generated_Code' => $this->whenLoaded('user_data')->generated_Code,
            'number_of_invites' => $this->whenLoaded('user_data')->number_of_invites,
            'nominated_by' => $this->whenLoaded('user_data')->nominated_by,
            'package_id' => $this->whenLoaded('user_data')->package_id,
            'package_name' => $this->whenLoaded('user_data')->package_id ? Package::query()->find($this->whenLoaded('user_data')->package_id)->package_name : '',
            'created_at' => $this->created_at->diffForHumans(),
            'user_sub_categories' => $user_sub_categories,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = Auth::user();
        $token = $user->createToken('MyApp')->plainTextToken;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'phone_number' => $this->phone_number,
            'phone_verify_at' => $this->phone_verify_at,
            'country_code' => $this->country_code,
            'is_like' => $this->is_like,
            'likes_count' => +$this->likes_count,
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
            'token' => $token
        ];
    }
}

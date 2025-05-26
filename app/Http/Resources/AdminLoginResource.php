<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AdminLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $admin = Auth::guard('admin')->user();
        $token = $admin->createToken('MyApp')->plainTextToken;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'token' => $token
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::where('id' , $this->id)->first();

        return [
            'service' => ServiceResource::collection($user->services),
            'tasks' => TaskResource::collection($user->tasks)
        ];
    }
}

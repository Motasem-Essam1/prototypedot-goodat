<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProviderContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => +$this->id,
            'user_id' => +$this->user_id,
            'visitor_id' => +$this->visitor_id,
            'visitor_type' => $this->visitor_type,
            'item_id' => +$this->item_id,
            'item_type' => $this->item_type,
            'provider' => $this->provider,
            'visitor' => $this->visitor,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}

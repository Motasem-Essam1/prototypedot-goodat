<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $array1 = [
            'id' => $this->id,
            'description' => $this->description,
            'rate' => +$this->rate,
            'quality' => +$this->quality,
            'time' => +$this->time,
            'accuracy' => +$this->accuracy,
            'communication' => +$this->communication,
            'customer_id' => +$this->customer_id,
            'created_at' => $this->created_at ? $this->created_at->diffForHumans() : "",
            'updated_at' => $this->updated_at ? $this->updated_at->diffForHumans() : "",
            'approvel' => +$this->approvel,
            'review_id' => +$this->review_id,
            'review_type' => $this->review_type,
        ];

        if($this->review_type == 'service')
        {
            $array1['service'] = $this->service ? ServiceResource::make($this->service) : "";
        }
        else if($this->review_type == 'provider')
        {
            $array1['user_name'] = $this->user->name;
            $array1['user_avatar'] = asset($this->user->user_data->avatar);
        }


        return $array1;
    }
}

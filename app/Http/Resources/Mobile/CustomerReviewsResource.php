<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerReviewsResource extends JsonResource
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
            'id' => $this->id,
            'description' => $this->description,
            'rate' => +$this->rate,
            'review_id' => $this->review_id,
            'review_type' => $this->review_type,
            'user_sub_categories_text' => $this->user_sub_categories_text,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_status' => $this->user->status,
            'user_likes_count' => +$this->user->likes_count,
            'user_is_like' => $this->user->is_like,
            'user_avatar' => asset($this->user->user_data->avatar),
            'user_phone_status' => $this->user->user_data->phone_status,
            'customer_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'customer_status' => $this->customer->status,
            'customer_likes_count' => +$this->customer->likes_count,
            'customer_is_like' => $this->customer->is_like,
            'customer_avatar' => asset($this->customer->user_data->avatar),
            'customer_phone_status' => $this->customer->user_data->phone_status,
        ];
    }
}

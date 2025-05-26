<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryActiveResource extends JsonResource
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
            'category_id' => +$this->category_id,
            'is_active'       => +$this->is_active,
            //'category_name' => $this->whenLoaded('parentCategory') ? $this->whenLoaded('parentCategory')->category_name : '',
            'sub_category_name' => $this->sub_category_name,
            'sub_category_slug' => $this->sub_category_slug,
            'sub_category_image' => asset($this->sub_category_image),
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}

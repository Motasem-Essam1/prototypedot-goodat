<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSubCategoriesResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user_category_id' => $this->user_category_id,
            'created_at' => $this->created_at ?  $this->created_at->diffForHumans() : $this->created_at,
            'updated_at' => $this->updated_at ? $this->updated_at->diffForHumans() : $this->updated_at,
            'sub_categories'  => SubCategoryActiveResource::make($this->whenLoaded('SubCategory')),

        ];
    }
}

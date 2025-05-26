<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryActiveResource extends JsonResource
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
            'category_name'   => $this->category_name,
            'category_slug'   => $this->category_slug,
            'category_image'  => asset($this->category_image),
            'is_active'       => +$this->is_active,
            'created_at' => $this->created_at->diffForHumans(),
            'sub_categories'  => SubCategoryActiveResource::collection($this->whenLoaded('subCategoriesActive')),
        ];
    }
}

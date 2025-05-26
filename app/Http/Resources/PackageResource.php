<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'package_name' => $this->package_name,
            'number_of_services' => +$this->number_of_services,
            'number_of_images_per_service' => +$this->number_of_images_per_service,
            'search_package_priority' => $this->search_package_priority,
            'tasks_notification_criteria' => $this->tasks_notification_criteria,
            'has_price' => $this->has_price,
            'has_condition' => $this->has_condition,
            'is_public' => $this->is_public,
            'per_month' => $this->per_month,
            'price' => +$this->price,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image ? asset($this->image) : asset('assets/img/subscriptions/avatar.svg'),
            'color' => $this->color,
            'phone_status' => $this->phone_status,
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}

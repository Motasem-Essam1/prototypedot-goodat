<?php

namespace App\Http\Resources\Mobile;

use App\Http\Resources\ImageResource;
use App\Models\CustomerReview;
use App\Services\LocationService;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{

    public static $latitude = 0;
    public static $longitude = 0;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if($request['latitude'] == 0 && $request['longitude'] == 0)
        {
            $location  = 0.0;
        }
        else{
            $location = LocationService::haversineGreatCircleDistance(TaskResource::$latitude, TaskResource::$longitude, $this->location_lat, $this->location_lng);
        }

        $image = $this->images->first() ? asset($this->images->first()->image_path) : '';


        $customer_review = CustomerReview::query()->where('customer_id', $this->user->id)->where('review_id', $this->id)->where('review_type', 'task')->first();

        return [
            'id' => $this->id,
            //'average' => $this->average,
            'average' => $this->CustomerReview ? +sprintf("%0.2f", $this->CustomerReview->rate) : 0,
            'user_average' => $customer_review ? + $customer_review->rate : 0,
            'user' => $this->user->name,
            'user_id' => $this->user->id,
//            'user_avatar' => getenv('APP_URL'). "/" . $this->user->user_data->avatar,
            'user_avatar' => asset($this->whenLoaded('user')->user_data->avatar),
            'user_status' => $this->user->status,
            'parent_category_id' => $this->parent_category_id,
            'parent_category' => $this->parentCategory ? $this->parentCategory->category_name : '',
            'category_id' => $this->category_id,
            'category' => $this->category ? $this->category->sub_category_name : '',
            'task_image' => $image,
            'task_name' => $this->task_name,
            'task_slug' => $this->task_slug,
            'task_description' => $this->task_description,
            'starting_price' => +$this->starting_price,
            'ending_price' => +$this->ending_price,
            'location_lng' => +$this->location_lng,
            'location_lat' => +$this->location_lat,
            'location' => +$location,
            'is_active' => +$this->is_active,
            'is_like' => $this->is_like,
            'likes_count' => +$this->likes_count,
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}

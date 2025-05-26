<?php

namespace App\Http\Resources;

use App\Models\Configuration;
use App\Models\Package;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $packages = Package::where('is_public', true)->get();
        $configurations = Configuration::query()->where("key", "currency_symbol")->first();

        return [
            'type' => $this->user_data->user_type,
            'packages' => PackageResource::collection($packages),
            'current_package' => $this->user_data->package,
            'configurations' => ConfigurationResource::make($configurations),
        ];
    }
}

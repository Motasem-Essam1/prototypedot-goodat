<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'transaction_id' => $this->transaction_id,
            'local_token' => $this->local_token,
            'amount_subtotal' => $this->amount_subtotal,
            'request_create_at' => $this->request_create_at,
            'currency' => $this->currency,
            'expires_at' => $this->expires_at,
            'transaction_at' => $this->transaction_at,
            'url' => $this->url,
            'status' => $this->status,
            'user_name' => $this->user->name,
            'user_id' => $this->user->id,
            'package_name' => $this->package->package_name,
            'package_id' => $this->package->id,
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}

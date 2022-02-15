<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\Resource as UserResource;
use App\Http\Resources\Product\Resource as ProductResource;
use App\Http\Resources\ShirtOption\Resource as ShirtOptionResource;
use App\Http\Resources\OrderStatus\Resource as OrderStatusResource;

class Resource extends JsonResource
{
    public static $wrap = 'order';
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
            'customer' => new UserResource($this->whenLoaded('customer')),
            'product' => new ProductResource($this->whenLoaded('product')),
            'shirt_option' => new ShirtOptionResource($this->whenLoaded('shirtOption')),
            'status' => new OrderStatusResource($this->whenLoaded('status')),
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\User\Resource as UserResource;
use App\Http\Resources\OrderStatus\Resource as OrderStatusResource;
use App\Http\Resources\OrderItem\Collection as OrderItemCollection;

class Collection extends ResourceCollection
{
    public static $wrap = 'orders';
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $collection = $this->collection->map(function ($item, $key) {
            $response = [
                'id' => $item->id,
                'customer' => new UserResource($item->whenLoaded('customer')),
                'status' => new OrderStatusResource($item->whenLoaded('status')),
                'items' => new OrderItemCollection($item->whenLoaded('orderItems')),
                'total_price' => $item->whenLoaded('orderItems')->sum('price'),
                'total_quantity' => $item->whenLoaded('orderItems')->sum('quantity'),
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ];
            return $response;
        });

        return $collection;
    }
}

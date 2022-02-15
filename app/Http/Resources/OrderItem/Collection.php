<?php

namespace App\Http\Resources\OrderItem;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Product\Resource as ProductResource;
use App\Http\Resources\ShirtOption\Resource as ShirtOptionResource;

class Collection extends ResourceCollection
{
    public static $wrap = 'order_items';
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
                'product' => new ProductResource($item->product),
                'shirt_option' => new ShirtOptionResource($item->shirtOption),
                'price' => $item->price,
                'quantity' => $item->quantity,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ];
            return $response;
        });

        return $collection;
    }
}

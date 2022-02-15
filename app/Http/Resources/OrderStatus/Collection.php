<?php

namespace App\Http\Resources\OrderStatus;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Collection extends ResourceCollection
{
    public static $wrap = 'order_statuses';
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
                'name' => $item->name,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ];
            return $response;
        });

        return $collection;
    }
}

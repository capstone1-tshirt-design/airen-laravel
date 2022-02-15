<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\User\Resource as UserResource;

class Collection extends ResourceCollection
{
    public static $wrap = 'reviews';
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
                'feedback' => $item->feedback,
                'user' => new UserResource($item->user),
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ];
            return $response;
        });

        return $collection;
    }
}

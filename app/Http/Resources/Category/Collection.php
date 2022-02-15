<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\User\Resource as UserResource;

class Collection extends ResourceCollection
{
    public static $wrap = 'categories';
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
                'description' => $item->description,
                'created_at' => $item->created_at,
                'created_by' => new UserResource($item->whenLoaded('createdBy')),
                'updated_at' => $item->updated_at,
                'updated_by' => new UserResource($item->whenLoaded('updatedBy')),
                'deleted_at' => $item->deleted_at,
                'deleted_by' => new UserResource($item->whenLoaded('deletedBy')),
            ];

            return $response;
        });

        return $collection;
    }
}

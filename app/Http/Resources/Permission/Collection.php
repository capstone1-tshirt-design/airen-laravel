<?php

namespace App\Http\Resources\Permission;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class Collection extends ResourceCollection
{
    public static $wrap = 'permissions';
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $collection = $this->collection->map(function ($item, $key) {
            return $item->name;
        });

        return $collection;
    }
}

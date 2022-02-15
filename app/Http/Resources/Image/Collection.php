<?php

namespace App\Http\Resources\Image;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class Collection extends ResourceCollection
{
    public static $wrap = 'images';
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $collection = $this->collection->map(function ($item, $key) {
            $url = preg_match('/http|https/i', $item->url) === 1 ? $item->url : Storage::temporaryUrl(
                $item->url,
                now()->addMinutes(5)
            );
            $response = [
                'id' => $item->id,
                'url' => $url,
                'name' => $item->name,
                'extension' => $item->extension,
                'size' => $item->size,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
            return $response;
        });

        return $collection;
    }
}

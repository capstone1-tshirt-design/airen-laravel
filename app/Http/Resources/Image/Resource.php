<?php

namespace App\Http\Resources\Image;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Resource extends JsonResource
{
    public static $wrap = 'image';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $url = preg_match('/http|https/i', $this->url) === 1 ? $this->url : Storage::temporaryUrl(
            $this->url,
            now()->addMinutes(5)
        );
        return [
            'id' => $this->id,
            'url' => $url,
            'name' => $this->name,
            'extension' => $this->extension,
            'size' => $this->size,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

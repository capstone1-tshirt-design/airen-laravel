<?php

namespace App\Http\Resources\ShirtOption;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public static $wrap = 'shirt_option';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'collar' => $this->collar,
            'shirt_length' => $this->shirt_length,
            'sleeve_length' => $this->sleeve_length,
            'chest' => $this->chest,
            'tummy' => $this->tummy,
            'hips' => $this->hips,
            'cuff' => $this->cuff,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

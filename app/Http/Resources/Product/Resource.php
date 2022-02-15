<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\Collection as CategoryCollection;
use App\Http\Resources\Image\Collection as ImageCollection;
use App\Http\Resources\Review\Collection as ReviewCollection;
use App\Http\Resources\User\Resource as UserResource;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Builder;

class Resource extends JsonResource
{
    public static $wrap = 'product';
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
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'categories' => new CategoryCollection($this->whenLoaded('categories')),
            'price' => $this->price,
            'old_price' => $this->old_price,
            'sale' => $this->sale,
            'reviews' => new ReviewCollection($this->whenLoaded('reviews')),
            'images' => new ImageCollection($this->whenLoaded('images')),
            'created_at' => $this->created_at,
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'updated_at' => $this->updated_at,
            'updated_by' => new UserResource($this->whenLoaded('updatedBy')),
            'deleted_at' => $this->deleted_at,
            'deleted_by' => new UserResource($this->whenLoaded('deletedBy')),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        $favorites = Favorite::with(['product']);

        $favorites
            ->whereRelation('user', 'id', $request->user)
            ->whereRelation('product', 'id', $this->id);

        return [
            'favorites' => $favorites->get()->pluck('product.id')->toArray()
        ];
    }
}

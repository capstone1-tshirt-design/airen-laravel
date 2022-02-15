<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Category\Collection as CategoryCollection;
use App\Http\Resources\Image\Collection as ImageCollection;
use App\Http\Resources\User\Resource as UserResource;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Builder;

class Collection extends ResourceCollection
{
    public static $wrap = 'products';

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
                'code' => $item->code,
                'description' => $item->description,
                'price' => $item->price,
                'old_price' => $item->old_price,
                'sale' => $item->sale,
                'categories' => new CategoryCollection($item->whenLoaded('categories')),
                'images' => new ImageCollection($item->whenLoaded('images')),
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

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        $favorites = Favorite::with(['product']);
        $productIds = $this->collection->pluck('id')->toArray();

        $favorites->whereRelation('user', 'id', $request->user);

        $favorites->whereHas('product', function (Builder $query) use ($request, $productIds) {
            $query
                ->whereIn('id', $productIds);
        });
        return [
            'favorites' => $favorites->get()->pluck('product.id')->toArray()
        ];
    }
}

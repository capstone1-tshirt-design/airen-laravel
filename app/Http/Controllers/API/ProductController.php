<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Http\Resources\Product\Collection as ProductCollection;
use App\Http\Resources\Review\Collection as ReviewCollection;
use App\Http\Resources\Product\Resource as ProductResource;
use App\Http\Requests\Product\Store;
use App\Http\Requests\Product\Update;
use App\Models\OrderItem;
use App\Models\Image;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;

class ProductController extends Controller
{
    /**
     * Path directory of upload
     *
     * @var        string
     */
    private $uploadPath;

    public function __construct()
    {
        $this->uploadPath = 'uploads/' . App::environment() . '/products';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::with(
            [
                'categories',
                'images',
                'createdBy',
                'createdBy.roles',
                'updatedBy',
                'updatedBy.roles',
                'deletedBy',
                'deletedBy.roles',
                'reviews',
                'reviews.user.image',
                'reviews.user.roles',
                'reviews.user.status'
            ]
        );

        if ($request->boolean('trashed')) {
            $products->withTrashed();
        }

        if ($request->has('category')) {
            $products->whereRelation('categories', 'name', $request->category);
        }

        if ($request->has('categories')) {
            $categories = explode(',', $request->categories);

            $products->whereHas('categories', function (Builder $query) use ($categories) {
                foreach ($categories as $category) {
                    $query->where('id', $category);
                }
            });
        }

        if ($request->has('favorite')) {
            $products->whereRelation('favorite.user', 'id', $request->favorite);
        }

        if ($request->has('createdBy')) {
            $products->whereRelation('createdBy', 'id', $request->createdBy);
        }

        if ($request->has('updatedBy')) {
            $products->whereRelation('updatedBy', 'id', $request->updatedBy);
        }

        if ($request->has('q')) {
            $q = trim($request->q) . '%';

            $products->where('name', 'like', $q);
        }

        if ($request->has('filter')) {
            $filter = '%' . trim($request->filter) . '%';
            $products->where(function (Builder $query) use ($filter) {
                $query
                    ->where('id', 'like', $filter)
                    ->orWhere('name', 'like', $filter)
                    ->orWhere('code', 'like', $filter)
                    ->orWhere('price', 'like', $filter)
                    ->orWhereRelation('createdBy', 'first_name', 'like', $filter)
                    ->orWhereRelation('createdBy', 'last_name', 'like', $filter)
                    ->orWhereRelation('updatedBy', 'first_name', 'like', $filter)
                    ->orWhereRelation('updatedBy', 'last_name', 'like', $filter);
            });
        }

        if ($request->has('except')) {
            $products->where('id', '<>', $request->except);
        }

        if ($request->has(['sortBy', 'sortDirection'])) {
            switch ($request->sortBy) {
                case 'created_by.full_name':
                    $products->orderBy(User::select('last_name')->whereColumn('users.id', 'products.created_by_id'), $request->sortDirection);
                    break;
                case 'updated_by.full_name':
                    $products->orderBy(User::select('last_name')->whereColumn('users.id', 'products.updated_by_id'), $request->sortDirection);
                    break;
                default:
                    $products->orderBy($request->sortBy, $request->sortDirection);
            }
        }

        if ($request->has('limit')) {
            return new ProductCollection($products->limit($request->limit)->get());
        }

        if ($request->has('picker') && $request->boolean('picker')) {
            $products->limit(10);
            return new ProductCollection($products->get());
        } else {
            return new ProductCollection($products->paginate($request->has('perPage') ? $request->perPage : 9));
        }
    }

    public function reviews(Request $request, Product $product)
    {
        $reviews = $product->reviews()->with([
            'user',
            'user.image',
            'user.roles',
            'user.status'
        ]);

        return new ReviewCollection($reviews->paginate(5));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        extract($request->validated());

        $categories = Category::whereIn('id', $categories)->get();
        $product = new Product;

        $product->name = $name;
        $product->code = $code;
        $product->description = $description;
        $product->price = $price;

        $product->createdBy()->associate($request->user());
        $product->updatedBy()->associate($request->user());

        $product->save();

        $product->categories()->attach($categories);

        $imgs = [];
        foreach ($images as $img) {
            $image = $img->store($this->uploadPath . '/' . $product->id);
            array_push($imgs, new Image([
                'url' => $image,
                'name' => $img->hashName(),
                'extension' => $img->extension(),
                'size' => $img->getSize()
            ]));
        }

        $product->images()->saveMany($imgs);

        return response(null, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Product $product)
    {
        $lastId = Product::withoutGlobalScopes()->orderBy('id', 'desc')->first()->id;

        $products = new ProductResource($product->load([
            'categories',
            'images'
        ]));

        if ($request->boolean('meta')) {
            $products->additional(['meta' => [
                'previous' => $product->id > 1 ? Product::where('id', '<', $product->id)->first()->id : null,
                'next' => $product->id < $lastId ? Product::where('id', '>', $product->id)->first()->id : null,
            ]]);
        }

        return $products;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Product $product)
    {
        extract($request->validated());
        $orders = OrderItem::whereRelation('product', 'id', $product->id)->get();

        $orders->each(function ($order) use ($categories) {
            $shirtOption = $order->shirtOption();
            $categories = Category::whereIn('id', $categories);
            if ($categories->where('name', 'shirt')->count() === 1 && !is_null($shirtOption)) {
                $shirtOption->dissociate();
                $order->save();
                $shirtOption->delete();
            }
        });

        $product->name = $name;
        $product->code = $code;
        $product->description = $description;
        $product->price = $price;
        $product->sale = $request->boolean('sale');
        if ($product->sale) {
            $product->old_price = $oldPrice;
        } else {
            $product->old_price = null;
        }

        $product->updatedBy()->associate($request->user());

        $product->save();

        // Categories
        $removedCategories = array_values(array_diff($product->categories()->pluck('id')->toArray(), $categories));

        if (count($removedCategories) > 0) {
            $product->categories()->detach($removedCategories);
        }

        $nonExistingCategories = array_values(array_diff($categories, $product->categories()->pluck('id')->toArray()));

        if (count($nonExistingCategories) > 0) {
            $product->categories()->attach($nonExistingCategories);
        }

        // Images
        if (isset($loadedImages)) {
            $product->images->each(function ($image) use ($loadedImages) {
                if (!in_array($image->id, $loadedImages) && Storage::exists($image->url)) {
                    Storage::delete($image->url);
                }
            });
            $removedImages = array_values(array_diff($product->images()->pluck('id')->toArray(), $loadedImages));

            if (count($removedImages) > 0) {
                $product->images()->whereIn('id', $removedImages)->delete();
            }
        } else {
            if (Storage::exists($this->uploadPath . '/' . $product->id)) {
                Storage::deleteDirectory($this->uploadPath . '/' . $product->id);
            }

            $product->images()->delete();
        }

        if (isset($images)) {
            $imgs = [];
            foreach ($images as $img) {
                $image = $img->store($this->uploadPath . '/' . $product->id);
                array_push($imgs, new Image([
                    'url' => $image,
                    'name' => $img->hashName(),
                    'extension' => $img->extension(),
                    'size' => $img->getSize()
                ]));
            }

            $product->images()->saveMany($imgs);
        }

        return response(null, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Product $product)
    {
        $product->deletedBy()->associate($request->user());
        $product->save();
        $product->delete();

        return response([], 204);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $product = Product::withTrashed()->find($id);
        $product->deletedBy()->dissociate();
        $product->save();
        $product->restore();

        return response([], 202);
    }
}

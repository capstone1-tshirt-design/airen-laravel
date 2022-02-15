<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\Category\Collection as CategoryCollection;
use App\Http\Resources\Category\Resource as CategoryResource;
use App\Http\Requests\Category\Store;
use App\Http\Requests\Category\Update;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::with([
            'createdBy',
            'createdBy.roles',
            'updatedBy',
            'updatedBy.roles'
        ]);

        if ($request->boolean('trashed')) {
            $categories->withTrashed();
        }

        if ($request->has('limit')) {
            $categories->inRandomOrder();
            $categories->limit($request->limit);
        }

        if ($request->has('createdBy')) {
            $categories->whereRelation('createdBy', 'id', $request->createdBy);
        }

        if ($request->has('updatedBy')) {
            $categories->whereRelation('updatedBy', 'id', $request->updatedBy);
        }

        if ($request->has('filter')) {
            $filter = trim($request->filter) . '%';
            $categories->where(function ($query) use ($filter) {
                $query
                    ->where('name', 'like', $filter)
                    ->orWhere('description', 'like', $filter);
            });
        }

        if ($request->has('picker') && $request->boolean('picker')) {
            return new CategoryCollection($categories->get());
        } else {
            return new CategoryCollection($categories->paginate($request->has('perPage') ? $request->perPage : 9));
        }
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
        $category = new Category;
        $category->name = $name;
        $category->description = $description;

        $category->createdBy()->associate($request->user());
        $category->updatedBy()->associate($request->user());

        $category->save();

        return response(null, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Category $category)
    {
        extract($request->validated());
        $category->name = $name;
        $category->description = $description;

        $category->updatedBy()->associate($request->user());

        $category->save();

        return response(null, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Category $category)
    {
        $category->deletedBy()->associate($request->user());
        $category->save();
        $category->delete();

        return response(null, 204);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $category = Category::withTrashed()->find($id);
        $category->deletedBy()->dissociate();
        $category->save();
        $category->restore();

        return response([], 202);
    }
}

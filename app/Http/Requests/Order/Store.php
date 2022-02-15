<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Product;

class Store extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $product = Product::find($this->product);

        return [
            'customer' => 'required|integer',
            'product' => 'required|integer',
            'collar' => [
                Rule::requiredIf(function () use ($product) {
                    return !is_null($product) && $product->whereRelation('categories', 'name', 'shirt')->count() > 0;
                }),
                'numeric'
            ],
            'shirt_length' => [
                Rule::requiredIf(function () use ($product) {
                    return !is_null($product) && $product->whereRelation('categories', 'name', 'shirt')->count() > 0;
                }),
                'numeric'
            ],
            'sleeve_length' => [
                Rule::requiredIf(function () use ($product) {
                    return !is_null($product) && $product->whereRelation('categories', 'name', 'shirt')->count() > 0;
                }),
                'numeric'
            ],
            'shoulder' => [
                Rule::requiredIf(function () use ($product) {
                    return !is_null($product) && $product->whereRelation('categories', 'name', 'shirt')->count() > 0;
                }),
                'numeric'
            ],
            'chest' => [
                Rule::requiredIf(function () use ($product) {
                    return !is_null($product) && $product->whereRelation('categories', 'name', 'shirt')->count() > 0;
                }),
                'numeric'
            ],
            'tummy' => [
                Rule::requiredIf(function () use ($product) {
                    return !is_null($product) && $product->whereRelation('categories', 'name', 'shirt')->count() > 0;
                }),
                'numeric'
            ],
            'hips' => [
                Rule::requiredIf(function () use ($product) {
                    return !is_null($product) && $product->whereRelation('categories', 'name', 'shirt')->count() > 0;
                }),
                'numeric'
            ],
            'cuff' => [
                Rule::requiredIf(function () use ($product) {
                    return !is_null($product) && $product->whereRelation('categories', 'name', 'shirt')->count() > 0;
                }),
                'numeric'
            ],
            'quantity' => 'required|integer',
            'image' => 'required|image'
        ];
    }
}

<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Category;

class Update extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|alpha_num',
            'description' => 'nullable',
            'price' => 'required|numeric|min:1',
            'sale' => 'boolean',
            'oldPrice' => 'nullable|numeric|min:1',
            'images.*' => 'mimetypes:image/jpeg,image/png,image/gif|max:1024',
            'images' => [
                Rule::requiredIf(isset($this->loadedImages) && count($this->loadedImages) === 0)
            ],
            'loadedImages' => [
                Rule::requiredIf(isset($this->images) && count($this->images) === 0)
            ],
            'categories' => 'required',
            'categories.*' => 'numeric|min:1',
        ];
    }
}

<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as RulesPassword;

class EditProfile extends FormRequest
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
            'first_name' => [
                'required',
                'alpha'
            ],
            'last_name' => [
                'required',
                'alpha'
            ],
            'address' => [
                'required'
            ],
            'phone' => [
                'required',
                'regex:/\+639[0-9]{9}/'
            ],
            'new_password' => [
                'filled',
                'confirmed',
                RulesPassword::defaults()
            ],
            'image' => [
                'mimetypes:image/jpeg,image/png,image/gif',
                'max:1024'
            ]
        ];
    }
}

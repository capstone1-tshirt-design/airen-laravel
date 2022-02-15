<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\UserStatus;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password as RulesPassword;

class Register extends FormRequest
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
            'birthdate' => [
                'required',
                'date_format:Y-m-d',
                'before:today'
            ],
            'gender' => [
                'required',
                'boolean'
            ],
            'username' => [
                'required',
                'string',
                'min:6',
                'max:15',
                'regex:/^[a-z0-9_.]{0,}$/',
                Rule::unique('users')
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users'),
            ],
            'password' => ['confirmed', RulesPassword::defaults()],
            'agree' => ['required', 'accepted']
        ];
    }

    public function createUser()
    {
        $status = UserStatus::where('name', 'active')->first();
        $role = Role::findByName('customer', 'api');

        $user = new User;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->birthdate = $this->birthdate;
        $user->gender = $this->gender;
        $user->address = $this->address;
        $user->phone = $this->phone;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = bcrypt($this->password);
        $user->provider_name = 'system';
        $user->provider_id = -1;

        $user->status()->associate($status);
        $user->save();

        $user->assignRole($role);

        return $user;
    }
}

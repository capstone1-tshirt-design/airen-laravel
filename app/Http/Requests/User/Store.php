<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\Image;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\App;

class Store extends FormRequest
{
    /**
     * Path directory of upload
     *
     * @var        string
     */
    private $uploadPath;

    /**
     * Default password for empty password
     * @var string
     */
    private $defaultPassword;

    public function __construct()
    {
        $this->uploadPath = 'uploads/' . App::environment() . '/users';
        $this->defaultPassword = bcrypt('123456');
    }
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
            'image' => [
                'mimetypes:image/jpeg,image/png,image/gif',
                'max:1024'
            ]
        ];
    }

    public function createUser()
    {
        extract($this->validated());
        $status = UserStatus::where('name', 'active')->first();
        $role = Role::findByName('administrator', 'api');

        $user = new User;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->birthdate = $birthdate;
        $user->gender = $gender;
        $user->address = $address;
        $user->phone = $phone;
        $user->username = $username;
        $user->email = $email;
        $user->password = isset($password) ? bcrypt($password) : $this->defaultPassword;
        $user->provider_name = 'system';
        $user->provider_id = -1;

        $user->status()->associate($status);
        $user->save();
        $user->markEmailAsVerified();

        if (isset($image)) {
            $img = $image->store($this->uploadPath . '/' . $user->id);

            $user->image()->save(new Image([
                'url' => $img,
                'name' => $image->hashName(),
                'extension' => $image->extension(),
                'size' => $image->getSize()
            ]));
        }

        $user->assignRole($role);
    }
}

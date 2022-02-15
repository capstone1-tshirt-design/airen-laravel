<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Image;
use App\Models\UserStatus;

class SocialAuthController extends Controller
{
    public function redirectToProvider(Request $request, $provider)
    {
        return Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
    }

    public function handleProviderCallback(Request $request, $provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        $name = explode(' ', $user->getName());
        $status = UserStatus::where('name', 'active')->first();

        $avatarOriginal = $user->getAvatar();
        $providerId = $user->getId();
        $user = User::firstOrCreate([
            'email' => $user->getEmail(),
        ], [
            'status_id' => $status->id,
            'password' => bcrypt(Str::random(12)),
            'first_name' => $name[0],
            'last_name' => isset($name[1]) ? $name[1] : null,
            'username' => str_replace(' ', '.', Str::lower($user->getName())),
            'provider_id' => $providerId,
            'provider_name' => $provider,
        ]);
        $image = new Image;
        $extensions = ['gif', 'jpg', 'png'];
        $image->url = $avatarOriginal;
        $image->name = $provider;
        $image->extension = $extensions[exif_imagetype($avatarOriginal) - 1];
        $image->size = get_headers($avatarOriginal, true)['Content-Length'];
        $user->image()->save($image);

        Auth::login($user);

        $user->save();
        $user->markEmailAsVerified();
        $role = Role::findByName('customer', 'api');

        $user->assignRole($role);
        $token = $user->createToken($user->email . '-' . $request->ip() . '-' . time());

        return redirect(url(env('SPA_URL') . 'reset-password?userToken=' . $token->plainTextToken));
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\User\Resource as UserResource;
use App\Http\Resources\Review\Resource as ReviewResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\Login;
use App\Http\Requests\Auth\Register;
use App\Http\Requests\Auth\EditProfile;
use App\Http\Requests\Auth\ConfirmPassword;
use Illuminate\Auth\Events\Registered;
use App\Models\Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Favorite;
use App\Models\Review;
use App\Http\Requests\Review\Store;
use App\Http\Requests\Review\Update;

class AuthController extends Controller
{
    /**
     * Path directory of upload
     *
     * @var        string
     */
    private $uploadPath;

    public function __construct()
    {
        $this->uploadPath = 'uploads/' . App::environment() . '/users';
    }

    public function loggedUser(Request $request)
    {
        return (new UserResource(Auth::user()->load([
            'roles',
            'roles.permissions',
            'status',
            'image',
            'favorites',
            'favorites.product'
        ])));
    }

    public function updateProfile(EditProfile $request)
    {
        extract($request->validated());

        $user = $request->user();

        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->phone = $phone;
        $user->address = $address;

        if (isset($password)) {
            $user->password = bcrypt($password);
        }

        $user->image()->delete();
        $user->save();

        if (Storage::exists($this->uploadPath . '/' . $user->id)) {
            Storage::deleteDirectory($this->uploadPath . '/' . $user->id);
        }
        $img = $image->store($this->uploadPath . '/' . $user->id);

        $i = new Image;

        $i->url = $img;
        $i->name = $image->hashName();
        $i->extension = $image->extension();
        $i->size = $image->getSize();

        $user->image()->save($i);

        return response(null, 202);
    }

    public function addFavorite(Request $request, Product $product)
    {
        $user = $request->user();

        $favorite = new Favorite;
        $favorite->product()->associate($product);
        $favorite->user()->associate($user);

        $favorite->save();

        return response(null, 202);
    }

    public function removeFavorite(Request $request, Product $product)
    {
        $user = $request->user();

        $user->favorites()->whereRelation('product', 'id', $product->id)->delete();
        $user->save();

        return response(null, 204);
    }

    public function addReview(Store $request, Product $product)
    {
        extract($request->validated());
        $user = $request->user();

        $review = new Review;
        $review->product()->associate($product);
        $review->user()->associate($user);
        $review->feedback = $feedback;

        $review->save();

        return response(null, 201);
    }

    public function updateReview(Update $request, Review $review)
    {
        extract($request->validated());
        $review->feedback = $feedback;

        $review->save();

        return response(null, 202);
    }

    public function deleteReview(Request $request, Review $review)
    {
        $review->delete();

        return response(null, 204);
    }

    public function confirmPassword(ConfirmPassword $request)
    {
        extract($request->validated());

        if (!Hash::check($current_password, $request->user()->password)) {
            return response(null, 401);
        }

        return response(null, 200);
    }

    public function register(Register $request)
    {
        $user = $request->createUser();

        event(new Registered($user));

        $token = $user->createToken($user->email . '-' . $request->ip() . '-' . time());

        return response($token->plainTextToken, 201);
    }

    public function login(Login $request)
    {
        $request->authenticate();

        if ($request->user()->status->name === 'Blocked') {
            return response(null, 403);
        }
        $user = $request->user();

        $token = $user->createToken($user->email . '-' . $request->ip() . '-' . time());

        return response($token->plainTextToken);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $user->last_active_at = now()->toDateTimeString();
        $user->save();

        return response([
            'message' => 'Logged out'
        ], 204);
    }
}

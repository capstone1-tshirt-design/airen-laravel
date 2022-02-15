<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;
use App\Http\Resources\Permission\Collection as PermissionCollection;
use App\Http\Resources\User\Resource as UserResource;
use App\Http\Resources\UserStatus\Resource as UserStatusResource;
use App\Http\Resources\Image\Resource as ImageResource;

class Collection extends ResourceCollection
{
    public static $wrap = 'users';

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
                'first_name' => $item->first_name,
                'last_name' => $item->last_name,
                'full_name' => $item->full_name,
                'gender' => $item->gender,
                'address' => $item->address,
                'phone' => $item->phone,
                'username' => $item->username,
                'email' => $item->email,
                'birthdate' => $item->birthdate,
                'email_verified_at' => $item->email_verified_at,
                'login_count' => $item->login_count,
                'last_login_at' => $item->last_login_at,
                'last_active_at' => $item->last_active_at,
                'provider_name' => $item->provider_name,
                'provider_id' => $item->provider_id,
                'status' => new UserStatusResource($item->whenLoaded('status')),
                'image' => new ImageResource($item->whenLoaded('image')),
                'permissions' => new PermissionCollection($item->whenLoaded('roles.permissions')),
                'role' => Str::title($item->whenLoaded('roles')->first()->name),
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'deleted_at' => $item->deleted_at,
                'deleted_by' => new UserResource($item->whenLoaded('deletedBy')),
            ];
            return $response;
        });

        return $collection;
    }
}

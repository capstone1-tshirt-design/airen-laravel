<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Permission\Collection as PermissionCollection;
use App\Http\Resources\User\Resource as UserResource;
use App\Http\Resources\UserStatus\Resource as UserStatusResource;
use App\Http\Resources\Image\Resource as ImageResource;

class Resource extends JsonResource
{
    public static $wrap = 'user';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'address' => $this->address,
            'phone' => $this->phone,
            'username' => $this->username,
            'email' => $this->email,
            'birthdate' => $this->birthdate,
            'email_verified_at' => $this->email_verified_at,
            'login_count' => $this->login_count,
            'last_login_at' => $this->last_login_at,
            'last_active_at' => $this->last_active_at,
            'provider_name' => $this->provider_name,
            'provider_id' => $this->provider_id,
            'status' => new UserStatusResource($this->whenLoaded('status')),
            'image' => new ImageResource($this->whenLoaded('image')),
            'role' => $this->whenLoaded('roles')->first()->name,
            'permissions' => new PermissionCollection($this->whenLoaded('roles.permissions')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => new UserResource($this->whenLoaded('deletedBy')),
        ];
    }
}

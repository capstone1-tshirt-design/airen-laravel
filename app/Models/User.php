<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'address',
        'phone',
        'username',
        'email',
        'birthdate',
        'password',
        'login_count',
        'last_login_at',
        'last_active_at',
        'provider_name',
        'provider_id',
        'status_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'last_active_at' => 'datetime',
        'gender' => 'boolean'
    ];

    /**
     * Global Scopes
     */
    protected static function booted()
    {
        static::addGlobalScope('sortByLastNameFirstName', function (Builder $builder) {
            $builder
                ->orderBy('last_name', 'asc')
                ->orderBy('first_name', 'asc');
        });
    }

    /**
     * Relationships
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function createdProducts()
    {
        return $this->hasMany(Product::class, 'created_by_id');
    }

    public function updatedProducts()
    {
        return $this->hasMany(Product::class, 'updated_by_id');
    }

    public function deletedProducts()
    {
        return $this->hasMany(Product::class, 'deleted_by_id');
    }

    public function createdCategories()
    {
        return $this->hasMany(Category::class, 'created_by_id');
    }

    public function updatedCategories()
    {
        return $this->hasMany(Category::class, 'updated_by_id');
    }

    public function deletedCategories()
    {
        return $this->hasMany(Category::class, 'deleted_by_id');
    }

    public function status()
    {
        return $this->belongsTo(UserStatus::class, 'status_id');
    }

    /**
     * Accessors
     */
    public function getFirstNameAttribute($value)
    {
        return Str::title($value);
    }

    public function getLastNameAttribute($value)
    {
        return Str::title($value);
    }

    public function getFullNameAttribute()
    {
        if (is_null($this->last_name)) {
            return $this->first_name;
        }
        return $this->last_name . ', ' . $this->first_name;
    }

    public function getAddressAttribute($value)
    {
        return Str::title($value);
    }

    public function getProviderNameAttribute($value)
    {
        return Str::title($value);
    }
}

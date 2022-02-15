<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'sale',
        'old_price',
        'price',
        'created_by_id',
        'updated_by_id',
        'deleted_by_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sale' => 'boolean'
    ];

    protected static function booted()
    {
        static::addGlobalScope('sortBySaleAndLatest', function (Builder $builder) {
            $builder
                ->orderBy('sale', 'desc')
                ->orderBy('created_at', 'desc');
        });
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function favorite()
    {
        return $this->hasOne(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class);
    }
}

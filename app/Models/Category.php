<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'created_by_id',
        'updated_by_id',
        'deleted_by_id'
    ];

    protected static function booted()
    {
        // static::addGlobalScope('sortByLatest', function (Builder $builder) {
        //     $builder
        //         ->orderBy('created_at', 'desc');
        // });
    }

    /**
     * Relationships
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
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

    /**
     * Accessors
     */
    public function getNameAttribute($value)
    {
        return Str::title($value);
    }

    public function scopeFindByName($query, $categoryName)
    {
        return $query->where('name', $categoryName)->first();
    }
}

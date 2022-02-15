<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'feedback',
        'user_id',
        'product_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope('sortBySaleAndLatest', function (Builder $builder) {
            $builder
                ->orderBy('created_at', 'desc');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderStatus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name'
    ];

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    /**
     * Accessors
     */
    public function getNameAttribute($value)
    {
        return Str::title($value);
    }
}

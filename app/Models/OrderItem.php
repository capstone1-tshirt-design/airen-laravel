<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'shirt_option_id',
        'price',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shirtOption()
    {
        return $this->hasOne(ShirtOption::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

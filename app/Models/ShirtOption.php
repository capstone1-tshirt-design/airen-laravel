<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShirtOption extends Model
{
    use HasFactory;

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function frontImage()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function backImages()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}

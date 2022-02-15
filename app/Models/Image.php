<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'name',
        'extension',
        'size'
    ];

    public function imageable()
    {
        return $this->morphTo(__FUNCTION__, 'imageable_type', 'imageable_id');
    }
}

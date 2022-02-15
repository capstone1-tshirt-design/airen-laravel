<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserStatus extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sortedByName', function ($query) {
            $query->orderBy('name', 'asc');
        });
    }

    public function users()
    {
        return $this->hasMany(User::class, 'status_id');
    }

    /**
     * Accessors
     */
    public function getNameAttribute($value)
    {
        return Str::title($value);
    }
}

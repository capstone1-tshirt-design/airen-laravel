<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;
    use Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'status_id'
    ];

    public $incrementing = false;

    protected static function booted()
    {
        static::addGlobalScope('sortBySaleAndLatest', function (Builder $builder) {
            $builder
                ->orderBy('created_at', 'desc');
        });
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

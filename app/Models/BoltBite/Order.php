<?php

namespace App\Models\BoltBite;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\OrderItem;
use App\Models\Comment;
use App\Models\DeliveryEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'total',
        'status',
        'delivery_address',
        'contact_phone',
        'notes',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(DeliveryEvent::class)->orderBy('occurred_at');
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            if ($order->total < 0) {
                throw new \InvalidArgumentException('Order total must be non-negative.');
            }
        });

        static::updating(function ($order) {
            if (isset($order->total) && $order->total < 0) {
                throw new \InvalidArgumentException('Order total must be non-negative.');
            }
        });
    }
}
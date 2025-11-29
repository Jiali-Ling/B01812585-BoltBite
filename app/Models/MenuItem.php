<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'restaurant_id',
        'user_id',
        'name',
        'category',
        'description',
        'price',
        'stock',
        'status',
        'image_path',
        'is_available',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function merchant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }

        if (str_starts_with($this->image_path, 'images/')) {
            $path = str_replace(' ', '%20', $this->image_path);
            return asset($path);
        }

        return asset('storage/' . $this->image_path);
    }

    protected static function booted()
    {
        static::creating(function ($menuItem) {
            if (empty($menuItem->name)) {
                throw new \InvalidArgumentException('Menu item name is required.');
            }
            if ($menuItem->price < 0) {
                throw new \InvalidArgumentException('Menu item price must be non-negative.');
            }
        });

        static::updating(function ($menuItem) {
            if ($menuItem->price < 0) {
                throw new \InvalidArgumentException('Menu item price must be non-negative.');
            }
        });
    }
}


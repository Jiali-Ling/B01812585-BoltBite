<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'content',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(\App\Models\BoltBite\Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($comment) {
            if (empty($comment->content)) {
                throw new \InvalidArgumentException('Comment content is required.');
            }
            if ($comment->rating < 1 || $comment->rating > 5) {
                throw new \InvalidArgumentException('Comment rating must be between 1 and 5.');
            }
        });
    }
}


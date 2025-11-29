<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'path',
        'filename',
        'mime',
        'size',
    ];

    public function post()
    {
        return $this->belongsTo(\App\Models\Post::class);
    }
}

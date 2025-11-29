<?php

namespace App\Models\BoltBite;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['name', 'address', 'is_open'];
    
    protected $casts = [
        'is_open' => 'boolean',
    ];
}
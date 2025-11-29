<?php

namespace App\Models\BoltBite;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['restaurant_id', 'name', 'price', 'image'];
}
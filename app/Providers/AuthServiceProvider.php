<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Post::class => \App\Policies\PostPolicy::class,
        \App\Models\Restaurant::class => \App\Policies\RestaurantPolicy::class,
        \App\Models\MenuItem::class => \App\Policies\MenuItemPolicy::class,
        \App\Models\Comment::class => \App\Policies\CommentPolicy::class,
        \App\Models\BoltBite\Order::class => \App\Policies\OrderPolicy::class,
        \App\Models\Review::class => \App\Policies\ReviewPolicy::class,
    ];

    public function boot(): void
    {
    }
}

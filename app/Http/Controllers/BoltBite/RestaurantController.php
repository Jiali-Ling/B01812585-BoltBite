<?php

namespace App\Http\Controllers\BoltBite;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\MenuItem;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with(['owner'])
            ->whereHas('menuItems', function ($query) {
                $query->where('status', 'on_shelf')
                      ->where('is_available', true);
            })
            ->paginate(12);
        
        return view('restaurants.index', ['restaurants' => $restaurants]);
    }

    public function show($id)
    {
        $restaurant = Restaurant::with(['owner'])->findOrFail($id);
        $menuItems = \App\Models\MenuItem::where('restaurant_id', $id)
            ->where('status', 'on_shelf')
            ->where('is_available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();
        
        $menuByCategory = $menuItems->groupBy(fn ($item) => $item->category ?? 'Other');
        
        return view('restaurants.show', [
            'restaurant' => $restaurant,
            'menuByCategory' => $menuByCategory,
        ]);
    }

    public function showMenuItem($menuItem)
    {
        $item = MenuItem::with(['restaurant', 'reviews.user', 'reviews.replies.user'])
            ->where('status', 'on_shelf')
            ->where('is_available', true)
            ->findOrFail($menuItem);
        
        return view('menu-items.show', [
            'menuItem' => $item,
            'restaurant' => $item->restaurant,
        ]);
    }
}

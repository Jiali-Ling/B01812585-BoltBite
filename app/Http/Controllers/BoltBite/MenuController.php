<?php

namespace App\Http\Controllers\BoltBite;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request, Restaurant $restaurant)
    {
        $this->authorize('manage', $restaurant);

        $query = $restaurant->menuItems()
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $menuItems = $query->get();
        $onShelfCount = $restaurant->menuItems()->where('status', 'on_shelf')->count();
        $offShelfCount = $restaurant->menuItems()->where('status', 'off_shelf')->count();

        return view('merchant.menu.index', [
            'restaurant' => $restaurant,
            'menuItems' => $menuItems,
            'onShelfCount' => $onShelfCount,
            'offShelfCount' => $offShelfCount,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoltBite\Order;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $stats = [
            'restaurants' => Restaurant::count(),
            'menuItems' => MenuItem::count(),
            'customers' => User::where('role', 'user')->count(),
            'merchants' => User::where('role', 'merchant')->count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
        ];

        $recentOrders = Order::with(['restaurant', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $merchantSignups = User::where('role', 'merchant')
            ->withCount('restaurants')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'merchantSignups'));
    }
}


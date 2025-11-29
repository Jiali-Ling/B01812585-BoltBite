<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\BoltBite\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a list of orders for the restaurants owned by the authenticated merchant.
     */
    public function index(Request $request)
    {
        $merchant = $request->user();
        $statusOptions = [
            'pending',
            'confirmed',
            'preparing',
            'ready',
            'out_for_delivery',
            'delivered',
            'cancelled',
        ];

        $restaurantIds = $merchant->restaurants()->pluck('id');
        $hasRestaurants = $restaurantIds->isNotEmpty();

        $ordersQuery = Order::with(['user', 'restaurant', 'items.menuItem'])
            ->whereIn('restaurant_id', $restaurantIds);

        $statusFilter = $request->query('status');
        if ($statusFilter && in_array($statusFilter, $statusOptions, true)) {
            $ordersQuery->where('status', $statusFilter);
        }

        $search = $request->query('search');
        if ($search) {
            $ordersQuery->where(function ($query) use ($search) {
                $query->where('id', (int) $search)
                    ->orWhere('contact_phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $hasRestaurants
            ? $ordersQuery->orderByDesc('created_at')->paginate(10)->withQueryString()
            : collect();

        return view('merchant.orders', [
            'orders' => $orders,
            'statusOptions' => $statusOptions,
            'statusFilter' => $statusFilter,
            'search' => $search,
            'hasRestaurants' => $hasRestaurants,
        ]);
    }
}


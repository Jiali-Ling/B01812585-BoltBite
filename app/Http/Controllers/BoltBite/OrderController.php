<?php

namespace App\Http\Controllers\BoltBite;

use App\Http\Controllers\Controller;
use App\Models\BoltBite\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\DeliveryEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Order::with(['restaurant', 'items.menuItem', 'events'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        $orders = $query->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'delivery_address' => 'required|string|max:500',
            'contact_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cartItems = CartItem::with('menuItem')
            ->where('user_id', $user->id)
            ->whereHas('menuItem', function ($query) use ($validated) {
                $query->where('restaurant_id', $validated['restaurant_id'])
                    ->where('status', 'on_shelf')
                    ->where('is_available', true);
            })
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors(['cart' => 'Your cart is empty or contains invalid items.']);
        }

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($cartItems as $cartItem) {
                if ($cartItem->menuItem->stock < $cartItem->quantity) {
                    throw new \Exception("Insufficient stock for {$cartItem->menuItem->name}");
                }
                $total += $cartItem->menuItem->price * $cartItem->quantity;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'restaurant_id' => $validated['restaurant_id'],
                'total' => $total,
                'status' => 'pending',
                'delivery_address' => $validated['delivery_address'],
                'contact_phone' => $validated['contact_phone'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $cartItem->menu_item_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->menuItem->price,
                    'item_name' => $cartItem->menuItem->name,
                ]);

                $cartItem->menuItem->decrement('stock', $cartItem->quantity);
            }

            DeliveryEvent::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'description' => 'Order placed',
                'occurred_at' => now(),
            ]);

            CartItem::where('user_id', $user->id)
                ->whereIn('menu_item_id', $cartItems->pluck('menu_item_id'))
                ->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['user', 'restaurant', 'items.menuItem', 'events', 'comments.user']);

        return view('orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['pending', 'confirmed', 'preparing', 'ready', 'out_for_delivery', 'delivered', 'cancelled']),
            ],
            'description' => 'nullable|string|max:500',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        if ($oldStatus !== $validated['status']) {
            DeliveryEvent::create([
                'order_id' => $order->id,
                'status' => $validated['status'],
                'description' => $validated['description'] ?? "Status changed to {$validated['status']}",
                'occurred_at' => now(),
            ]);
        }

        return back()->with('success', 'Order status updated.');
    }
}

<?php

namespace App\Http\Controllers\BoltBite;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('add', 'remove');
    }

    public function show()
    {
        return view('boltbite.cart');
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $menuItem = MenuItem::where('id', $data['menu_item_id'])
            ->where('status', 'on_shelf')
            ->where('is_available', true)
            ->firstOrFail();

        $quantity = min($data['quantity'] ?? 1, $menuItem->stock);

        $cartItem = CartItem::firstOrNew([
            'user_id' => $request->user()->id,
            'menu_item_id' => $menuItem->id,
        ]);

        $cartItem->quantity = min($menuItem->stock, ($cartItem->quantity ?? 0) + $quantity);
        $cartItem->save();

        return back()->with('success', 'Item added to your cart.');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
        ]);

        CartItem::where('user_id', $request->user()->id)
            ->where('menu_item_id', $request->integer('menu_item_id'))
            ->delete();

        return back()->with('success', 'Item removed from your cart.');
    }
}

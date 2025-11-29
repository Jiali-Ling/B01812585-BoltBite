<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CartTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_add_item_to_cart()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $menuItem = MenuItem::factory()->create([
            'restaurant_id' => $restaurant->id,
            'stock' => 10,
            'is_available' => true
        ]);

        $response = $this->actingAs($user)
            ->post(route('cart.add'), [
                'menu_item_id' => $menuItem->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'menu_item_id' => $menuItem->id,
            'quantity' => 1
        ]);
    }

    public function test_guest_cannot_add_item_to_cart()
    {
        $restaurant = Restaurant::factory()->create();
        $menuItem = MenuItem::factory()->create([
            'restaurant_id' => $restaurant->id,
            'stock' => 10,
            'is_available' => true
        ]);

        $response = $this->post(route('cart.add'), [
            'menu_item_id' => $menuItem->id,
        ]);

        $response->assertRedirect(route('login'));
    }
}

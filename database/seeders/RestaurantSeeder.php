<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\MenuItem;

class RestaurantSeeder extends Seeder
{
    public function run(int $ownerId = null)
    {
        $restaurantsData = [
            [
                'name' => 'Bella Napoli',
                'description' => 'Authentic Italian cuisine with handmade pasta and wood-fired pizzas.',
                'rating' => 4.8,
                'image' => 'images/Information/Images/Bella Napoli/Bella Napoli.png',
                'menu_items' => [
                    ['name' => 'Pizza margherita', 'category' => 'PIZZA', 'price' => 14.99, 'image' => 'images/Information/Images/Bella Napoli/Pizza margherita.png', 'description' => 'Fresh tomatoes, mozzarella, basil'],
                    ['name' => 'Pepperoni pizza rose', 'category' => 'PIZZA', 'price' => 16.99, 'image' => 'images/Information/Images/Bella Napoli/Pepperoni pizza rose.png', 'description' => 'Classic pepperoni with mozzarella'],
                    ['name' => 'Spaghetti alla Carbonara', 'category' => 'PASTA', 'price' => 15.99, 'image' => 'images/Information/Images/Bella Napoli/Spaghetti alla Carbonara.png', 'description' => 'Creamy pasta with bacon and parmesan'],
                    ['name' => 'Lasagna Bolognese', 'category' => 'PASTA', 'price' => 16.99, 'image' => 'images/Information/Images/Bella Napoli/Lasagna Bolognese.png', 'description' => 'Layers of pasta with meat sauce and cheese'],
                    ['name' => 'Tiramisu', 'category' => 'DESSERT', 'price' => 7.99, 'image' => 'images/Information/Images/Bella Napoli/Tiramisu.png', 'description' => 'Classic Italian coffee-flavored dessert'],
                ],
            ],
            [
                'name' => 'Burger Haven',
                'description' => 'Gourmet burgers and classic American comfort food.',
                'rating' => 4.6,
                'image' => 'images/Information/Images/Burger Haven/Burger Haven.png',
                'menu_items' => [
                    ['name' => 'Classic Cheeseburger', 'category' => 'BURGER', 'price' => 12.99, 'image' => 'images/Information/Images/Burger Haven/Classic Cheeseburger.png', 'description' => 'Beef patty with cheese, lettuce, tomato, and special sauce'],
                    ['name' => 'Bacon Burger', 'category' => 'BURGER', 'price' => 14.99, 'image' => 'images/Information/Images/Burger Haven/Bacon Burger.png', 'description' => 'Juicy beef patty with crispy bacon and cheddar'],
                    ['name' => 'Chicken Wings (6pcs)', 'category' => 'APPETIZER', 'price' => 9.99, 'image' => 'images/Information/Images/Burger Haven/Chicken Wings (6pcs).png', 'description' => 'Spicy buffalo wings with ranch dip'],
                    ['name' => 'french fries', 'category' => 'SIDE', 'price' => 4.99, 'image' => 'images/Information/Images/Burger Haven/french fries.png', 'description' => 'Crispy golden fries'],
                    ['name' => 'milkshake', 'category' => 'DRINK', 'price' => 5.99, 'image' => 'images/Information/Images/Burger Haven/milkshake.png', 'description' => 'Creamy vanilla milkshake'],
                ],
            ],
            [
                'name' => 'El Taco Loco',
                'description' => 'Authentic Mexican street food with bold flavors.',
                'rating' => 4.7,
                'image' => 'images/Information/Images/El Taco Loco/El Taco Loco.png',
                'menu_items' => [
                    ['name' => 'Beef Tacos (3pcs)', 'category' => 'TACOS', 'price' => 11.99, 'image' => 'images/Information/Images/El Taco Loco/Beef Tacos (3pcs).png', 'description' => 'Seasoned ground beef with fresh toppings'],
                    ['name' => 'Chicken Burrito', 'category' => 'BURRITO', 'price' => 13.99, 'image' => 'images/Information/Images/El Taco Loco/Chicken Burrito.png', 'description' => 'Grilled chicken with rice, beans, and cheese'],
                    ['name' => 'Cheese Quesadilla', 'category' => 'APPETIZER', 'price' => 8.99, 'image' => 'images/Information/Images/El Taco Loco/Cheese Quesadilla.png', 'description' => 'Melted cheese in a crispy tortilla'],
                    ['name' => 'Chips & Guacamole', 'category' => 'APPETIZER', 'price' => 6.99, 'image' => 'images/Information/Images/El Taco Loco/Chips & Guacamole.png', 'description' => 'Fresh guacamole with crispy tortilla chips'],
                    ['name' => 'Churros', 'category' => 'DESSERT', 'price' => 5.99, 'image' => 'images/Information/Images/El Taco Loco/Churros.png', 'description' => 'Cinnamon sugar churros with chocolate dip'],
                ],
            ],
            [
                'name' => 'Golden Dragon',
                'description' => 'Traditional Chinese cuisine with modern presentation.',
                'rating' => 4.5,
                'image' => 'images/Information/Images/Golden Dragon/Golden Dragon.png',
                'menu_items' => [
                    ['name' => 'Sweet & Sour Chicken', 'category' => 'MAIN', 'price' => 15.99, 'image' => 'images/Information/Images/Golden Dragon/Sweet & Sour Chicken.png', 'description' => 'Crispy chicken with tangy sweet and sour sauce'],
                    ['name' => 'Beef with Broccoli', 'category' => 'MAIN', 'price' => 16.99, 'image' => 'images/Information/Images/Golden Dragon/Beef with Broccoli.png', 'description' => 'Tender beef stir-fried with fresh broccoli'],
                    ['name' => 'Beef with BroccoliFried Rice', 'category' => 'RICE', 'price' => 12.99, 'image' => 'images/Information/Images/Golden Dragon/Beef with BroccoliFried Rice.png', 'description' => 'Fried rice with beef and vegetables'],
                    ['name' => 'Spring Rolls (4pcs)', 'category' => 'APPETIZER', 'price' => 6.99, 'image' => 'images/Information/Images/Golden Dragon/Spring Rolls (4pcs).png', 'description' => 'Crispy vegetable spring rolls'],
                    ['name' => 'dumplings 6pcs', 'category' => 'APPETIZER', 'price' => 8.99, 'image' => 'images/Information/Images/Golden Dragon/dumplings 6pcs.png', 'description' => 'Steamed pork dumplings'],
                ],
            ],
            [
                'name' => 'Sakura Sushi',
                'description' => 'Fresh sushi and authentic Japanese cuisine.',
                'rating' => 4.9,
                'image' => 'images/Information/Images/Sakura Sushi/Sakura Sushi.png',
                'menu_items' => [
                    ['name' => 'Salmon Nigiri (2pcs)', 'category' => 'SUSHI', 'price' => 9.99, 'image' => 'images/Information/Images/Sakura Sushi/Salmon Nigiri (2pcs).png', 'description' => 'Fresh salmon on seasoned rice'],
                    ['name' => 'California Roll', 'category' => 'SUSHI', 'price' => 8.99, 'image' => 'images/Information/Images/Sakura Sushi/California Roll.png', 'description' => 'Crab, avocado, and cucumber roll'],
                    ['name' => 'Chicken Teriyaki', 'category' => 'MAIN', 'price' => 14.99, 'image' => 'images/Information/Images/Sakura Sushi/Chicken Teriyaki.png', 'description' => 'Grilled chicken with teriyaki sauce'],
                    ['name' => 'Miso Soup', 'category' => 'SOUP', 'price' => 3.99, 'image' => 'images/Information/Images/Sakura Sushi/Miso Soup.png', 'description' => 'Traditional Japanese miso soup'],
                    ['name' => 'Green Tea Ice Cream', 'category' => 'DESSERT', 'price' => 5.99, 'image' => 'images/Information/Images/Sakura Sushi/Green Tea Ice Cream.png', 'description' => 'Creamy matcha ice cream'],
                ],
            ],
            [
                'name' => 'Spice Palace',
                'description' => 'Authentic Indian curries and traditional tandoori dishes.',
                'rating' => 4.6,
                'image' => 'images/Information/Images/Spice Palace/Spice Palace.png',
                'menu_items' => [
                    ['name' => 'Chicken Tikka Masala', 'category' => 'CURRY', 'price' => 16.99, 'image' => 'images/Information/Images/Spice Palace/Chicken Tikka Masala.png', 'description' => 'Creamy tomato curry with tender chicken'],
                    ['name' => 'Lamb Vindaloo', 'category' => 'CURRY', 'price' => 18.99, 'image' => 'images/Information/Images/Spice Palace/Lamb Vindaloo.png', 'description' => 'Spicy lamb curry with potatoes'],
                    ['name' => 'Samosas (3pcs)', 'category' => 'APPETIZER', 'price' => 5.99, 'image' => 'images/Information/Images/Spice Palace/Samosas (3pcs).png', 'description' => 'Crispy pastries filled with spiced potatoes'],
                    ['name' => 'Garlic Naan', 'category' => 'BREAD', 'price' => 3.99, 'image' => 'images/Information/Images/Spice Palace/Garlic Naan.png', 'description' => 'Fresh baked garlic naan bread'],
                    ['name' => 'Mango Lassi', 'category' => 'DRINK', 'price' => 4.99, 'image' => 'images/Information/Images/Spice Palace/Mango Lassi.png', 'description' => 'Sweet mango yogurt drink'],
                ],
            ],
        ];

        foreach ($restaurantsData as $data) {
            $restaurant = Restaurant::updateOrCreate(
                ['name' => $data['name']],
                [
                    'description' => $data['description'],
                    'rating' => $data['rating'],
                    'image' => $data['image'],
                    'user_id' => $ownerId,
                ]
            );

            foreach ($data['menu_items'] as $itemData) {
                MenuItem::updateOrCreate(
                    [
                        'restaurant_id' => $restaurant->id,
                        'name' => $itemData['name'],
                    ],
                    [
                        'user_id' => $ownerId,
                        'category' => $itemData['category'],
                        'price' => $itemData['price'],
                        'image_path' => $itemData['image'],
                        'description' => $itemData['description'] ?? null,
                        'stock' => 100,
                        'status' => 'on_shelf',
                        'is_available' => true,
                    ]
                );
            }
        }
    }
}

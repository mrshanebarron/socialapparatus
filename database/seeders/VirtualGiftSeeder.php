<?php

namespace Database\Seeders;

use App\Models\VirtualGift;
use App\Models\CoinPackage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VirtualGiftSeeder extends Seeder
{
    public function run(): void
    {
        // Virtual Gifts - using emoji as image placeholder
        $gifts = [
            // Love category
            ['name' => 'Heart', 'image' => 'gifts/heart.png', 'category' => 'love', 'coin_cost' => 5],
            ['name' => 'Kiss', 'image' => 'gifts/kiss.png', 'category' => 'love', 'coin_cost' => 10],
            ['name' => 'Rose', 'image' => 'gifts/rose.png', 'category' => 'love', 'coin_cost' => 25],
            ['name' => 'Bouquet', 'image' => 'gifts/bouquet.png', 'category' => 'love', 'coin_cost' => 50],
            ['name' => 'Ring', 'image' => 'gifts/ring.png', 'category' => 'love', 'coin_cost' => 500],

            // Celebration category
            ['name' => 'Confetti', 'image' => 'gifts/confetti.png', 'category' => 'celebration', 'coin_cost' => 10],
            ['name' => 'Balloon', 'image' => 'gifts/balloon.png', 'category' => 'celebration', 'coin_cost' => 15],
            ['name' => 'Trophy', 'image' => 'gifts/trophy.png', 'category' => 'celebration', 'coin_cost' => 100],
            ['name' => 'Crown', 'image' => 'gifts/crown.png', 'category' => 'celebration', 'coin_cost' => 200],
            ['name' => 'Star', 'image' => 'gifts/star.png', 'category' => 'celebration', 'coin_cost' => 50],

            // Fun category
            ['name' => 'Thumbs Up', 'image' => 'gifts/thumbsup.png', 'category' => 'fun', 'coin_cost' => 5],
            ['name' => 'Fire', 'image' => 'gifts/fire.png', 'category' => 'fun', 'coin_cost' => 10],
            ['name' => 'Rocket', 'image' => 'gifts/rocket.png', 'category' => 'fun', 'coin_cost' => 30],
            ['name' => 'Rainbow', 'image' => 'gifts/rainbow.png', 'category' => 'fun', 'coin_cost' => 25],
            ['name' => 'Unicorn', 'image' => 'gifts/unicorn.png', 'category' => 'fun', 'coin_cost' => 75],

            // Food category
            ['name' => 'Coffee', 'image' => 'gifts/coffee.png', 'category' => 'food', 'coin_cost' => 20],
            ['name' => 'Pizza', 'image' => 'gifts/pizza.png', 'category' => 'food', 'coin_cost' => 35],
            ['name' => 'Cake', 'image' => 'gifts/cake.png', 'category' => 'food', 'coin_cost' => 50],
            ['name' => 'Champagne', 'image' => 'gifts/champagne.png', 'category' => 'food', 'coin_cost' => 100],
            ['name' => 'Ice Cream', 'image' => 'gifts/icecream.png', 'category' => 'food', 'coin_cost' => 15],

            // Premium category
            ['name' => 'Diamond', 'image' => 'gifts/diamond.png', 'category' => 'premium', 'coin_cost' => 1000, 'is_premium' => true],
            ['name' => 'Gold Bar', 'image' => 'gifts/goldbar.png', 'category' => 'premium', 'coin_cost' => 500, 'is_premium' => true],
            ['name' => 'Treasure', 'image' => 'gifts/treasure.png', 'category' => 'premium', 'coin_cost' => 750, 'is_premium' => true],
            ['name' => 'Yacht', 'image' => 'gifts/yacht.png', 'category' => 'premium', 'coin_cost' => 2000, 'is_premium' => true],
            ['name' => 'Castle', 'image' => 'gifts/castle.png', 'category' => 'premium', 'coin_cost' => 5000, 'is_premium' => true],
        ];

        foreach ($gifts as $gift) {
            VirtualGift::firstOrCreate(
                ['name' => $gift['name']],
                array_merge($gift, [
                    'slug' => Str::slug($gift['name']),
                    'is_active' => true,
                    'is_premium' => $gift['is_premium'] ?? false,
                ])
            );
        }

        // Coin Packages
        $packages = [
            ['name' => 'Starter', 'coins' => 100, 'bonus_coins' => 0, 'price' => 0.99, 'is_featured' => false],
            ['name' => 'Small', 'coins' => 500, 'bonus_coins' => 25, 'price' => 4.99, 'is_featured' => false],
            ['name' => 'Medium', 'coins' => 1000, 'bonus_coins' => 100, 'price' => 9.99, 'is_featured' => true],
            ['name' => 'Large', 'coins' => 2500, 'bonus_coins' => 375, 'price' => 19.99, 'is_featured' => false],
            ['name' => 'XL', 'coins' => 5000, 'bonus_coins' => 1000, 'price' => 39.99, 'is_featured' => false],
            ['name' => 'Premium', 'coins' => 10000, 'bonus_coins' => 2500, 'price' => 74.99, 'is_featured' => false],
        ];

        foreach ($packages as $package) {
            CoinPackage::firstOrCreate(
                ['name' => $package['name']],
                array_merge($package, ['currency' => 'USD', 'is_active' => true])
            );
        }
    }
}

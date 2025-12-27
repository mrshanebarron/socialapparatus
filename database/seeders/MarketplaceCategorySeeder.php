<?php

namespace Database\Seeders;

use App\Models\MarketplaceCategory;
use Illuminate\Database\Seeder;

class MarketplaceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $icons = [
            'Vehicles' => '🚗',
            'Property Rentals' => '🏠',
            'Electronics' => '📱',
            'Clothing' => '👕',
            'Home & Garden' => '🏡',
            'Hobbies' => '⚽',
            'Family' => '👨‍👩‍👧',
            'Free Stuff' => '🎁',
        ];

        $sortOrder = 1;

        foreach (MarketplaceCategory::DEFAULT_CATEGORIES as $parentName => $children) {
            $parent = MarketplaceCategory::firstOrCreate(
                ['name' => $parentName],
                ['icon' => $icons[$parentName] ?? '📦', 'sort_order' => $sortOrder++]
            );

            foreach ($children as $childName) {
                MarketplaceCategory::firstOrCreate(
                    ['name' => $childName, 'parent_id' => $parent->id],
                    ['icon' => '', 'sort_order' => $sortOrder++]
                );
            }
        }
    }
}

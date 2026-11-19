<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or update the parent categories
        $food = Category::updateOrCreate(
            ['name' => 'Food'],
            [
                'parent_id' => null,
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // Reuse the existing Drink record instead of creating a duplicate.
        $drinks = Category::whereIn('name', ['Drink', 'Drinks'])->first()
            ?? new Category();

        $drinks->fill([
            'name' => 'Drinks',
            'parent_id' => null,
            'sort_order' => 2,
            'is_active' => true,
        ]);
        $drinks->save();

        $merch = Category::updateOrCreate(
            ['name' => 'Merch'],
            [
                'parent_id' => null,
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        // 2. Food subcategories
        $foodSubcategories = [
            'Sandwiches',
            'Pastries',
            'Salad',
            'Wraps',
        ];

        foreach ($foodSubcategories as $index => $name) {
            Category::updateOrCreate(
                ['name' => $name],
                [
                    'parent_id' => $food->id,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }

        // Reuse the existing Add On record and make it a Food subcategory.
        $addOns = Category::whereIn('name', ['Add On', 'Add Ons'])->first()
            ?? new Category();

        $addOns->fill([
            'name' => 'Add Ons',
            'parent_id' => $food->id,
            'sort_order' => 5,
            'is_active' => true,
        ]);
        $addOns->save();

        // 3. Drinks subcategories
        $drinkSubcategories = [
            'Hot Drinks',
            'Ice Ice Baby',
            'Signature',
        ];

        foreach ($drinkSubcategories as $index => $name) {
            Category::updateOrCreate(
                ['name' => $name],
                [
                    'parent_id' => $drinks->id,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
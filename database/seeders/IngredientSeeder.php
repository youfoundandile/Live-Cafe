<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            // Coffee, tea and drink bases
            [
                'name' => 'Espresso Beans',
                'quantity' => 10.000,
                'availability' => true,
            ],
            [
                'name' => 'Decaf Beans',
                'quantity' => 3.000,
                'availability' => true,
            ],
            [
                'name' => 'Chai Concentrate',
                'quantity' => 5.000,
                'availability' => true,
            ],
            [
                'name' => 'Cocoa Powder',
                'quantity' => 2.000,
                'availability' => true,
            ],
            [
                'name' => 'Matcha Powder',
                'quantity' => 1.000,
                'availability' => true,
            ],
            [
                'name' => 'Tea Bags',
                'quantity' => 100.000,
                'availability' => true,
            ],
            [
                'name' => 'Rooibos Tea Bags',
                'quantity' => 100.000,
                'availability' => true,
            ],

            // Milks, water and ice
            [
                'name' => 'Whole Milk',
                'quantity' => 20.000,
                'availability' => true,
            ],
            [
                'name' => 'Oat Milk',
                'quantity' => 8.000,
                'availability' => true,
            ],
            [
                'name' => 'Almond Milk',
                'quantity' => 8.000,
                'availability' => true,
            ],
            [
                'name' => 'Vanilla Milk',
                'quantity' => 15.000,
                'availability' => true,
            ],
            [
                'name' => 'Drinking Water',
                'quantity' => 100.000,
                'availability' => true,
            ],
            [
                'name' => 'Ice',
                'quantity' => 30.000,
                'availability' => true,
            ],

            // Syrups
            [
                'name' => 'Vanilla Syrup',
                'quantity' => 3.000,
                'availability' => true,
            ],

            // Fruit and vegetables
            [
                'name' => 'Lemons',
                'quantity' => 60.000,
                'availability' => true,
            ],
            [
                'name' => 'Strawberries',
                'quantity' => 4.000,
                'availability' => true,
            ],
            [
                'name' => 'Bananas',
                'quantity' => 10.000,
                'availability' => true,
            ],
            [
                'name' => 'Avocado',
                'quantity' => 6.000,
                'availability' => true,
            ],
            [
                'name' => 'Lettuce',
                'quantity' => 5.000,
                'availability' => true,
            ],
            [
                'name' => 'Tomato',
                'quantity' => 5.000,
                'availability' => true,
            ],
            [
                'name' => 'Cherry Tomatoes',
                'quantity' => 3.000,
                'availability' => true,
            ],
            [
                'name' => 'Red Onion',
                'quantity' => 3.000,
                'availability' => true,
            ],
            [
                'name' => 'Bell Peppers',
                'quantity' => 3.000,
                'availability' => true,
            ],
            [
                'name' => 'Cucumbers',
                'quantity' => 3.000,
                'availability' => true,
            ],
            [
                'name' => 'Carrots',
                'quantity' => 3.000,
                'availability' => true,
            ],

            // Proteins and fillings
            [
                'name' => 'Chicken Breast',
                'quantity' => 8.000,
                'availability' => true,
            ],
            [
                'name' => 'Tuna',
                'quantity' => 3.000,
                'availability' => true,
            ],
            [
                'name' => 'Pesto',
                'quantity' => 2.000,
                'availability' => true,
            ],
            [
                'name' => 'Mayo',
                'quantity' => 3.000,
                'availability' => true,
            ],
            [
                'name' => 'Sweet Chilli Mayo',
                'quantity' => 2.000,
                'availability' => true,
            ],
            [
                'name' => 'Jalapenos',
                'quantity' => 1.000,
                'availability' => true,
            ],
            [
                'name' => 'Tabasco',
                'quantity' => 1.000,
                'availability' => true,
            ],

            // Bread and baking
            [
                'name' => 'Panini Bread',
                'quantity' => 50.000,
                'availability' => true,
            ],
            [
                'name' => 'Tortilla Wrap',
                'quantity' => 40.000,
                'availability' => true,
            ],
            [
                'name' => 'Flour',
                'quantity' => 20.000,
                'availability' => true,
            ],
            [
                'name' => 'Butter',
                'quantity' => 8.000,
                'availability' => true,
            ],
            [
                'name' => 'Eggs',
                'quantity' => 80.000,
                'availability' => true,
            ],
            [
                'name' => 'Almonds',
                'quantity' => 1.000,
                'availability' => true,
            ],
            [
                'name' => 'Pecan Nuts',
                'quantity' => 2.000,
                'availability' => true,
            ],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;
use RuntimeException;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = array_merge(
            $this->food(),
            $this->hotDrinks(),
            $this->coldDrinks(),
            $this->signatureDrinks(),
            $this->addOns()
        );

        foreach ($products as $data) {
            $category = Category::where('name', $data['category'])->first();

            if (! $category) {
                throw new RuntimeException(
                    "Category [{$data['category']}] not found."
                );
            }

            $product = Product::create([
                'name' => $data['name'],
                'price' => $data['price'],
                'category_id' => $category->id,
                'description' => $data['description'],
                'prod_availability' => $data['prod_availability'],
                'quantity' => $data['quantity'],
            ]);

            foreach ($data['ingredients'] as $ingredientName => $quantityRequired) {
                $ingredient = Ingredient::where('name', $ingredientName)->first();

                if (! $ingredient) {
                    throw new RuntimeException(
                        "Ingredient [{$ingredientName}] not found."
                    );
                }

                $product->ingredients()->attach(
                    $ingredient->id,
                    ['quantity_required' => $quantityRequired]
                );
            }
        }
    }

    private function item(
        string $category,
        string $name,
        float $price,
        int $quantity,
        string $description,
        array $ingredients = [],
        bool $available = true
    ): array {
        return [
            'category' => $category,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'description' => $description,
            'prod_availability' => $available,
            'ingredients' => $ingredients,
        ];
    }

    /**
     * Creates a 250ml and a 350ml product from one hot drink.
     * Ingredients are defined for 250ml; everything except the
     * espresso/decaf beans is scaled up for the 350ml size.
     */
    private function hotSizes(
        string $name,
        float $price250,
        float $price350,
        string $description,
        array $ingredients,
        int $quantity = 35
    ): array {
        $large = [];
        foreach ($ingredients as $ingredient => $qty) {
            $isBeans = in_array($ingredient, ['Espresso Beans', 'Decaf Beans'], true);
            $large[$ingredient] = $isBeans ? $qty : round($qty * 1.4, 3);
        }

        return [
            $this->item('Drink', "{$name} (250ml)", $price250, $quantity, $description, $ingredients),
            $this->item('Drink', "{$name} (350ml)", $price350, $quantity, $description, $large),
        ];
    }

    private function food(): array
    {
        return [
            // Sandwiches (on panini bread)
            $this->item('Food', 'Club Anthem', 94.00, 20, 'Panini sandwich with chicken, pesto, avocado and tomato.', [
                'Panini Bread' => 1.000,
                'Chicken Breast' => 0.120,
                'Pesto' => 0.020,
                'Avocado' => 0.050,
                'Tomato' => 0.030,
            ]),
            $this->item('Food', 'Pescado', 72.00, 20, 'Panini sandwich with pesto, avocado and tomato.', [
                'Panini Bread' => 1.000,
                'Pesto' => 0.020,
                'Avocado' => 0.050,
                'Tomato' => 0.030,
            ]),
            $this->item('Food', 'Special T', 82.00, 20, 'Panini sandwich with tuna, pesto, avocado, tomato, jalapenos and tabasco.', [
                'Panini Bread' => 1.000,
                'Tuna' => 0.100,
                'Pesto' => 0.020,
                'Avocado' => 0.050,
                'Tomato' => 0.030,
                'Jalapenos' => 0.015,
                'Tabasco' => 0.005,
            ]),
            $this->item('Food', 'Grilled Chicken', 87.00, 20, 'Panini sandwich with chicken, red onion, lettuce and sweet chilli mayo.', [
                'Panini Bread' => 1.000,
                'Chicken Breast' => 0.120,
                'Red Onion' => 0.020,
                'Lettuce' => 0.030,
                'Sweet Chilli Mayo' => 0.020,
            ]),

            // Pastries
            $this->item('Food', 'Almond Croissant', 48.00, 25, 'Fresh almond croissant.', [
                'Flour' => 0.080,
                'Butter' => 0.030,
                'Almonds' => 0.020,
            ]),
            $this->item('Food', 'Butter Croissant', 35.00, 30, 'Fresh buttery croissant.', [
                'Flour' => 0.080,
                'Butter' => 0.030,
            ]),
            $this->item('Food', 'Butter Scone', 26.00, 30, 'Freshly baked butter scone.', [
                'Flour' => 0.100,
                'Butter' => 0.030,
            ]),
            $this->item('Food', 'Banana Loaf', 32.00, 25, 'Freshly baked banana loaf.', [
                'Flour' => 0.080,
                'Bananas' => 0.500,
                'Butter' => 0.020,
                'Eggs' => 1.000,
            ]),
            $this->item('Food', 'Carrot Cake', 36.00, 20, 'Slice of carrot cake.', [
                'Flour' => 0.080,
                'Carrots' => 0.050,
                'Eggs' => 1.000,
                'Butter' => 0.020,
            ]),
            $this->item('Food', 'Pecan Nut Squares', 52.00, 20, 'Pecan nut square.', [
                'Flour' => 0.060,
                'Butter' => 0.030,
                'Pecan Nuts' => 0.040,
            ]),

            // Salads
            $this->item('Food', 'Green Salad', 45.00, 20, 'Lettuce, bell peppers, avocado, cherry tomatoes, red onion, cucumbers and pesto.', [
                'Lettuce' => 0.080,
                'Bell Peppers' => 0.030,
                'Avocado' => 0.050,
                'Cherry Tomatoes' => 0.050,
                'Red Onion' => 0.020,
                'Cucumbers' => 0.040,
                'Pesto' => 0.015,
            ]),
            $this->item('Food', 'Chicken Salad', 60.00, 20, 'Green salad with chicken.', [
                'Lettuce' => 0.080,
                'Bell Peppers' => 0.030,
                'Avocado' => 0.050,
                'Cherry Tomatoes' => 0.050,
                'Red Onion' => 0.020,
                'Cucumbers' => 0.040,
                'Pesto' => 0.015,
                'Chicken Breast' => 0.120,
            ]),
            $this->item('Food', 'Tuna Salad', 50.00, 20, 'Green salad with tuna.', [
                'Lettuce' => 0.080,
                'Bell Peppers' => 0.030,
                'Avocado' => 0.050,
                'Cherry Tomatoes' => 0.050,
                'Red Onion' => 0.020,
                'Cucumbers' => 0.040,
                'Pesto' => 0.015,
                'Tuna' => 0.100,
            ]),

            // Wraps (no price shown on the menu - 65.00 kept from previous seeder)
            $this->item('Food', 'Chicken Wrap', 65.00, 30, 'Wrap with chicken, lettuce, red onion and mayo.', [
                'Chicken Breast' => 0.150,
                'Tortilla Wrap' => 1.000,
                'Lettuce' => 0.030,
                'Red Onion' => 0.020,
                'Mayo' => 0.020,
            ]),
        ];
    }

    private function hotDrinks(): array
    {
        return array_merge(
            $this->hotSizes('Americano', 35, 38, 'Espresso with hot water.', [
                'Espresso Beans' => 0.018, 'Drinking Water' => 0.250,
            ]),
            $this->hotSizes('Cappuccino', 38, 42, 'Espresso with steamed milk and foam.', [
                'Espresso Beans' => 0.018, 'Whole Milk' => 0.180,
            ]),
            $this->hotSizes('Chai Latte', 39, 42, 'Spiced chai with steamed milk.', [
                'Chai Concentrate' => 0.060, 'Whole Milk' => 0.200,
            ]),
            [$this->item('Drink', 'Cortado', 36, 35, 'Espresso cut with a little warm milk.', [
                'Espresso Beans' => 0.018, 'Whole Milk' => 0.080,
            ])],
            $this->hotSizes('Decaf', 41, 45, 'Decaf coffee with steamed milk.', [
                'Decaf Beans' => 0.018, 'Whole Milk' => 0.200,
            ]),
            $this->hotSizes('Dirty Chai', 46, 48, 'Chai latte with a shot of espresso.', [
                'Chai Concentrate' => 0.060, 'Espresso Beans' => 0.018, 'Whole Milk' => 0.180,
            ]),
            $this->hotSizes('Espresso', 25, 28, 'Strong, rich espresso.', [
                'Espresso Beans' => 0.018,
            ], 40),
            [$this->item('Drink', 'Flat White', 38, 35, 'Espresso with velvety steamed milk.', [
                'Espresso Beans' => 0.018, 'Whole Milk' => 0.150,
            ])],
            $this->hotSizes('Hot Chocolate', 36, 40, 'Rich hot chocolate.', [
                'Cocoa Powder' => 0.030, 'Whole Milk' => 0.220,
            ]),
            $this->hotSizes('Latte', 41, 45, 'Espresso with smooth steamed milk.', [
                'Espresso Beans' => 0.018, 'Whole Milk' => 0.250,
            ]),
            [$this->item('Drink', 'Macchiato', 31, 35, 'Espresso marked with a dash of foam.', [
                'Espresso Beans' => 0.018, 'Whole Milk' => 0.030,
            ])],
            $this->hotSizes('Matcha Latte', 65, 68, 'Matcha with steamed milk.', [
                'Matcha Powder' => 0.005, 'Whole Milk' => 0.220,
            ]),
            $this->hotSizes('Mocha', 45, 49, 'Espresso with chocolate and steamed milk.', [
                'Espresso Beans' => 0.018, 'Cocoa Powder' => 0.020, 'Whole Milk' => 0.200,
            ]),
            [$this->item('Drink', 'Piccolo', 34, 35, 'Short espresso with steamed milk.', [
                'Espresso Beans' => 0.018, 'Whole Milk' => 0.060,
            ])],
            $this->hotSizes('Red Cappuccino', 37, 40, 'Rooibos cappuccino with steamed milk and foam.', [
                'Rooibos Tea Bags' => 1.000, 'Whole Milk' => 0.180,
            ]),
            [$this->item('Drink', 'Tea', 30, 40, 'Hot tea.', [
                'Tea Bags' => 1.000, 'Drinking Water' => 0.250,
            ])]
        );
    }

    private function coldDrinks(): array
    {
        return [
            $this->item('Drink', 'Chai Freezo', 45, 30, 'Blended iced chai (500ml).', [
                'Chai Concentrate' => 0.080, 'Whole Milk' => 0.200, 'Ice' => 0.200,
            ]),
            $this->item('Drink', 'Freezo', 48, 30, 'Blended iced coffee drink (500ml).', [
                'Espresso Beans' => 0.018, 'Whole Milk' => 0.200, 'Ice' => 0.200,
            ]),
            $this->item('Drink', 'Iced Americano', 40, 35, 'Espresso over ice and water (500ml).', [
                'Espresso Beans' => 0.018, 'Drinking Water' => 0.250, 'Ice' => 0.200,
            ]),
            $this->item('Drink', 'Iced Chai', 47, 30, 'Chai over ice with milk (500ml).', [
                'Chai Concentrate' => 0.080, 'Whole Milk' => 0.250, 'Ice' => 0.200,
            ]),
            $this->item('Drink', 'Iced Latte', 48, 35, 'Espresso with cold milk over ice (500ml).', [
                'Espresso Beans' => 0.018, 'Whole Milk' => 0.250, 'Ice' => 0.200,
            ]),
            $this->item('Drink', 'Iced Matcha', 65, 30, 'Matcha based with oat milk, over ice (500ml). +R5 for syrup.', [
                'Matcha Powder' => 0.005, 'Oat Milk' => 0.250, 'Ice' => 0.200,
            ]),
            $this->item('Drink', 'Iced Mocha', 50, 30, 'Iced espresso with chocolate and milk (500ml).', [
                'Espresso Beans' => 0.018, 'Cocoa Powder' => 0.020, 'Whole Milk' => 0.220, 'Ice' => 0.200,
            ]),
            $this->item('Drink', 'Iced Red Cappuccino', 36, 30, 'Iced rooibos cappuccino (500ml).', [
                'Rooibos Tea Bags' => 1.000, 'Whole Milk' => 0.200, 'Ice' => 0.200,
            ]),
            $this->item('Drink', 'Iced Tea', 42, 30, 'Chilled iced tea (500ml).', [
                'Tea Bags' => 1.000, 'Drinking Water' => 0.300, 'Ice' => 0.200,
            ]),
            $this->item('Drink', 'Homemade Lemonade', 42, 30, 'Homemade lemonade (500ml).', [
                'Lemons' => 2.000, 'Drinking Water' => 0.300, 'Ice' => 0.150,
            ]),
            $this->item('Drink', 'Mocha Freezo', 52, 30, 'Blended iced mocha (500ml).', [
                'Espresso Beans' => 0.018, 'Cocoa Powder' => 0.020, 'Whole Milk' => 0.200, 'Ice' => 0.200,
            ]),
            $this->item('Drink', 'Still Water', 25, 40, 'Chilled still water.'),
            $this->item('Drink', 'Sparkling Water', 27, 40, 'Chilled sparkling water.'),
            $this->item('Drink', 'Iron Man', 42, 30, 'Iced signature drink (500ml).'),
        ];
    }

    private function signatureDrinks(): array
    {
        return [
            $this->item('Drink', 'The Gem In I', 63, 25, 'Strawberries, banana and vanilla milk (500ml).', [
                'Strawberries' => 0.080, 'Bananas' => 0.500, 'Vanilla Milk' => 0.250,
            ]),
            $this->item('Drink', 'Avo Bliss', 68, 25, 'Ice, avocado, banana and vanilla milk (500ml).', [
                'Ice' => 0.150, 'Avocado' => 0.100, 'Bananas' => 0.500, 'Vanilla Milk' => 0.250,
            ]),
            $this->item('Drink', 'Big Matcha', 90, 25, 'Ice, avocado, banana, vanilla milk and matcha (500ml).', [
                'Ice' => 0.150, 'Avocado' => 0.100, 'Bananas' => 0.500, 'Vanilla Milk' => 0.250, 'Matcha Powder' => 0.005,
            ]),
            $this->item('Drink', 'Arizz', 52, 25, 'Blended espresso and vanilla milk (500ml).', [
                'Espresso Beans' => 0.018, 'Vanilla Milk' => 0.250, 'Ice' => 0.150,
            ]),
            $this->item('Drink', 'Vanilla Latte', 62, 30, 'Espresso and vanilla milk (500ml).', [
                'Espresso Beans' => 0.018, 'Vanilla Milk' => 0.300,
            ]),
            $this->item('Drink', 'Iced Vanilla Latte', 65, 30, 'Ice, espresso, vanilla milk and vanilla syrup (500ml).', [
                'Ice' => 0.200, 'Espresso Beans' => 0.018, 'Vanilla Milk' => 0.250, 'Vanilla Syrup' => 0.015,
            ]),
        ];
    }

    private function addOns(): array
    {
        return [
            // Milk +12
            $this->item('Add On', 'Oat Milk Upgrade', 12, 30, 'Replace regular milk with oat milk.', ['Oat Milk' => 0.250]),
            $this->item('Add On', 'Almond Milk Upgrade', 12, 30, 'Replace regular milk with almond milk.', ['Almond Milk' => 0.250]),
            $this->item('Add On', 'Vanilla Milk Upgrade', 12, 30, 'Replace regular milk with vanilla milk.', ['Vanilla Milk' => 0.250]),

            // Syrups +5
            $this->item('Add On', 'Vanilla Syrup', 5, 30, 'Vanilla syrup add-on.'),
            $this->item('Add On', 'Strawberry Syrup', 5, 30, 'Strawberry syrup add-on.'),
            $this->item('Add On', 'Salted Caramel Syrup', 5, 30, 'Salted caramel syrup add-on.'),
            $this->item('Add On', 'Caramel Syrup', 5, 30, 'Caramel syrup add-on.'),
            $this->item('Add On', 'Hazelnut Syrup', 5, 30, 'Hazelnut syrup add-on.'),

            // Other
            $this->item('Add On', 'Extra Espresso Shot', 19.60, 40, 'Add an extra espresso shot.', ['Espresso Beans' => 0.018]),
            $this->item('Add On', 'Collagen', 20, 20, 'Collagen add-on.'),
            $this->item('Add On', 'Coffee Bag', 720, 15, 'Bag of coffee beans to take home.'),
        ];
    }
}
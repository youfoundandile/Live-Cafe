<?php

// tests/Feature/StockServiceTest.php
use App\Exceptions\InsufficientStockException;
use App\Models\Ingredient;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Services\Inventory\StockService;

//
function wrap(float $chicken, float $tortillas): Product
{
    $product = Product::factory()->create();
    $chickenIn = Ingredient::factory()->create(['name' => 'Test Chicken', 'quantity' => $chicken]);
    $wrapIn = Ingredient::factory()->create(['name' => 'Test Tortilla', 'quantity' => $tortillas]);

    $product->ingredients()->attach([
        $chickenIn->id => ['quantity_required' => 0.150],
        $wrapIn->id => ['quantity_required' => 1],
    ]);

    return $product->load('ingredients');
}

test('limits a made product by its scarcest ingredient', function () {
    expect(wrap(3.0, 10)->availableQuantity())->toBe(10);   // chicken allows 20, tortillas 10
});

test('never lets stock go below zero', function () {
    $product = wrap(3.0, 1);

    expect(fn () => app(StockService::class)->deduct($product, 2, 'test'))
        ->toThrow(InsufficientStockException::class);

    expect((float) Ingredient::where('name', 'Test Chicken')->value('quantity'))->toBe(3.0);
});

test('deducts every ingredient and logs each change', function () {
    app(StockService::class)->deduct(wrap(3.0, 10), 2, 'test');

    expect((float) Ingredient::where('name', 'Test Chicken')->value('quantity'))->toBe(2.7);
    expect((float) Ingredient::where('name', 'Test Tortilla')->value('quantity'))->toBe(8.0);
    expect(InventoryTransaction::count())->toBe(2);
});

test('deducts products without a recipe from their own quantity', function () {
    $shirt = Product::factory()->create(['quantity' => 5]);   // no ingredients attached
    app(StockService::class)->deduct($shirt, 2, 'test');

    expect($shirt->fresh()->quantity)->toBe(3);
});

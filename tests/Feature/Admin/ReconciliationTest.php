<?php

use App\Models\Product;
use App\Models\Sale;
use App\Models\SyncConflict;
use App\Models\User;
use App\Services\Admin\ReconciliationService;
use App\Services\Inventory\StockService;

test('records a conflict when an offline sale exceeds stock, and stock stops at zero', function () {
    $shirt = Product::factory()->create(['quantity' => 1]);
    $sale = Sale::factory()->create(['is_offline' => true]);

    $shortfalls = app(StockService::class)->deductForSync($shirt, 3, $sale);
    app(ReconciliationService::class)->record($sale, $shortfalls);

    expect($shirt->fresh()->quantity)->toBe(0)
        ->and(SyncConflict::open()->count())->toBe(1)
        ->and((float) SyncConflict::first()->shortfall)->toBe(2.0);
});

test('lets an admin resolve a conflict with a recount', function () {
    $this->actingAs(User::factory()->admin()->create());
    $shirt = Product::factory()->create(['quantity' => 0]);
    $sale = Sale::factory()->create();
    $conflict = SyncConflict::create([
        'sales_id' => $sale->id, 'stockable_type' => $shirt->getMorphClass(),
        'stockable_id' => $shirt->id, 'shortfall' => 2,
    ]);

    $this->patch(route('admin.sync.resolve', $conflict), [
        'resolution' => 'recount', 'note' => 'Found a box in storage', 'actual_count' => 10,
    ])->assertRedirect();

    expect($conflict->fresh()->status)->toBe('resolved')
        ->and($shirt->fresh()->quantity)->toBe(10);
});

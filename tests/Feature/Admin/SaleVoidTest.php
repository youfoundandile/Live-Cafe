<?php

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\User;

it('voiding a sale returns stock and records who and why', function () {
    $admin = User::factory()->admin()->create();
    $shirt = Product::factory()->create(['quantity' => 1]);
    $sale = Sale::factory()->create(['status' => 'confirmed']);
    SalesDetail::factory()->create(['sales_id' => $sale->id, 'product_id' => $shirt->id, 'quantity' => 2]);

    $this->actingAs($admin)
        ->post(route('admin.sales.void', $sale), ['reason' => 'Rang up twice by mistake'])
        ->assertRedirect();

    $sale->refresh();
    expect($sale->status)->toBe('cancelled')
        ->and($sale->voided_by)->toBe($admin->id)
        ->and($shirt->fresh()->quantity)->toBe(3)
        ->and(InventoryTransaction::where('reason', 'sale_voided')->count())->toBe(1);
});

it('does not let staff void sales', function () {
    $sale = Sale::factory()->create(['status' => 'confirmed']);

    $this->actingAs(User::factory()->staff()->create())
        ->post(route('admin.sales.void', $sale), ['reason' => 'Trying anyway'])
        ->assertForbidden();
});

test('needs a reason to void', function () {
    $sale = Sale::factory()->create(['status' => 'confirmed']);

    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.sales.void', $sale), ['reason' => ''])
        ->assertSessionHasErrors('reason');
});

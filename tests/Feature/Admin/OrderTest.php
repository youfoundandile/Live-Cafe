<?php

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;

beforeEach(fn () => $this->actingAs(User::factory()->admin()->create()));

test('cancelling an order puts stock back', function () {
    $shirt = Product::factory()->create(['quantity' => 3]);          // no recipe = counted
    $order = Order::factory()->create(['status' => 'pending']);
    OrderDetail::factory()->create(['order_id' => $order->id, 'product_id' => $shirt->id, 'quantity' => 2]);

    $this->patch(route('admin.orders.status', $order), ['status' => 'cancelled'])->assertRedirect();

    expect($order->fresh()->status)->toBe('cancelled');
    expect($shirt->fresh()->quantity)->toBe(5);
});

test('refuses an illegal status change', function () {
    $order = Order::factory()->create(['status' => 'collected']);

    $this->patch(route('admin.orders.status', $order), ['status' => 'pending']);

    expect($order->fresh()->status)->toBe('collected');
});
it('shows the status buttons on the order pages', function () {
    $order = Order::factory()->create(['status' => 'pending']);

    $this->get(route('admin.orders.index'))->assertOk()->assertSee('Mark confirmed');
    $this->get(route('admin.orders.show', $order))->assertOk()->assertSee('Mark cancelled');
});

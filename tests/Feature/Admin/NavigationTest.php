<?php

// testing the navigation links for the admin dashboard
use App\Models\User;

test('opens every admin page', function (string $route) {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin)->get(route($route))->assertOk();
})->with([
    'admin.dashboard',
    'admin.inventory.index',
    'admin.users.index',
    'admin.orders.index',
    'admin.products.index',
    'admin.events.index',
    'admin.sales.index',
    'admin.announcements.index',
    'admin.partnerships.index',
    'admin.sync.index',

]);

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
    'admin.categories.index',
    'admin.users.create',
    'admin.events.create',

]);

test('marks the current page in the sidebar', function () {
    $this->actingAs(User::factory()->admin()->create());

    $this->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee('class="active">Products', false);   // false: match the raw HTML
});

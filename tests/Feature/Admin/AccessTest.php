<?php

use App\Models\User;

test('lets admin into the admin area', function () {
    $this->actingAs(User::factory()->admin()->create());
    $this->get(route('admin.dashboard'))->assertOk();
});

test('blocks staff and customers from the admin area', function (string $role) {
    $this->actingAs(User::factory()->create(['role' => $role, 'email_verified_at' => now()]));
    $this->get(route('admin.dashboard'))->assertForbidden();
})->with(['staff', 'customer']);

test('guests are sent to login', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('unverifeied admin are sent to verify eamil', function () {
    $this->actingAs(User::factory()->admin()->unverified()->create());
    $this->get(route('admin.dashboard'))->assertRedirect(route('verification.notice'));

});

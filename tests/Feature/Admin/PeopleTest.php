<?php

use App\Models\Order;
use App\Models\Partnership;
use App\Models\PartnershipMember;
use App\Models\User;

test('lets an admin promote staff to admin', function () {
    $staff = User::factory()->staff()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.users.update', $staff), [
            'name' => $staff->name,
            'surname' => $staff->surname,
            'phone_number' => $staff->phone_number,
            'role' => 'admin',
        ])
        ->assertRedirect(route('admin.users.index'));

    expect($staff->fresh()->role)->toBe('admin');
});

test('does not let an admin demote themselves', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->put(route('admin.users.update', $admin), [
        'name' => $admin->name,
        'surname' => $admin->surname,
        'phone_number' => $admin->phone_number,
        'role' => 'staff',
    ]);

    expect($admin->fresh()->role)->toBe('admin');
});

test('will not delete a user who has orders', function () {
    $customer = User::factory()->customer()->create();
    Order::factory()->create(['user_id' => $customer->id]);

    $this->actingAs(User::factory()->admin()->create())
        ->delete(route('admin.users.destroy', $customer))
        ->assertSessionHas('error');

    expect(User::find($customer->id))->not->toBeNull();
});

test('adds a member, deactivates them, and re-adding switches them back on', function () {
    $partnership = Partnership::factory()->create();
    $person = User::factory()->customer()->create();
    $this->actingAs(User::factory()->admin()->create());

    $this->post(route('admin.partnerships.members.store', $partnership), [
        'email' => $person->email, 'employee_id' => 'SB-123456',
    ])->assertRedirect();

    $member = PartnershipMember::where('user_id', $person->id)->firstOrFail();
    expect($member->status)->toBeTrue();

    $this->delete(route('admin.partnerships.members.destroy', [$partnership, $member]))->assertRedirect();
    expect($member->fresh()->status)->toBeFalse();

    $this->post(route('admin.partnerships.members.store', $partnership), [
        'email' => $person->email, 'employee_id' => 'SB-123456',
    ]);
    expect(PartnershipMember::count())->toBe(1)
        ->and($member->fresh()->status)->toBeTrue();
});

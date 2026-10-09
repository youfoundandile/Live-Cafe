<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn ($q, $s) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$s}%")
                ->orWhere('surname', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%")))
            ->when($request->role, fn ($q, $r) => $q->where('role', $r))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return redirect()->route('admin.users.edit', $user);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'surname' => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'string', 'max:20'],
            'role' => ['required', 'in:customer,staff,admin'],
        ]);

        if ($user->is($request->user()) && $data['role'] !== 'admin') {
            return back()->with('error', "You can't remove your own admin role.");
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', "You can't delete your own account here.");
        }

        // orders, sales and partnership_members use restrictOnDelete, so MySQL would refuse anyway
        if ($user->orders()->exists() || $user->sales()->exists() || $user->partnershipMembers()->exists()) {
            return back()->with('error', 'This user has orders, sales or a partnership, so change their role instead.');
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }
}

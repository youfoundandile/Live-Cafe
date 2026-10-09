<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partnership;
use App\Models\PartnershipMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PartnershipController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Partnership::class);

        $partnerships = Partnership::withCount([
            'partnershipMembers as active_members_count' => fn ($q) => $q->where('status', true),
        ])->orderBy('name')->get();

        return view('admin.partnerships.index', compact('partnerships'));
    }

    public function create()
    {
        Gate::authorize('create', Partnership::class);

        return view('admin.partnerships.form', ['partnership' => new Partnership(['status' => true])]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Partnership::class);

        $partnership = Partnership::create($this->validated($request));

        return redirect()->route('admin.partnerships.show', $partnership)->with('success', 'Partnership created.');
    }

    public function show(Partnership $partnership)
    {
        Gate::authorize('view', $partnership);

        $partnership->load('partnershipMembers.user');

        return view('admin.partnerships.show', compact('partnership'));
    }

    public function edit(Partnership $partnership)
    {
        Gate::authorize('update', $partnership);

        return view('admin.partnerships.form', compact('partnership'));
    }

    public function update(Request $request, Partnership $partnership)
    {
        Gate::authorize('update', $partnership);

        $partnership->update($this->validated($request));

        return redirect()->route('admin.partnerships.show', $partnership)->with('success', 'Partnership updated.');
    }

    public function destroy(Partnership $partnership)
    {
        Gate::authorize('delete', $partnership);

        $partnership->update(['status' => false]);   // members use restrictOnDelete, so keep the row

        return back()->with('success', 'Partnership deactivated.');
    }

    public function addMember(Request $request, Partnership $partnership)
    {
        Gate::authorize('update', $partnership);

        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'employee_id' => ['required', 'string', 'max:100'],
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();

        // unique index on partnership_id + user_id: re-adding someone reactivates them
        $partnership->partnershipMembers()->updateOrCreate(
            ['user_id' => $user->id],
            ['employee_id' => $data['employee_id'], 'status' => true],
        );

        return back()->with('success', "{$user->name} added.");
    }

    public function removeMember(Partnership $partnership, PartnershipMember $member)
    {
        Gate::authorize('update', $partnership);

        abort_unless($member->partnership_id === $partnership->id, 404);   // must belong to this partnership

        $member->update(['status' => false]);   // keep history, don't delete

        return back()->with('success', 'Member deactivated.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);
    }
}

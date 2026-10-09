<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnnouncementManagementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(20);

        return view('admin.announcements.index', compact('announcements'));
    }

    // The "new" form sits at the top of the list page
    public function create()
    {
        return redirect()->route('admin.announcements.index');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Announcement::class);

        Announcement::create($this->validated($request));

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement posted.');
    }

    public function show(Announcement $announcement)
    {
        return redirect()->route('admin.announcements.edit', $announcement);
    }

    public function edit(Announcement $announcement)
    {
        Gate::authorize('update', $announcement);

        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        Gate::authorize('update', $announcement);

        $announcement->update($this->validated($request));

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        Gate::authorize('delete', $announcement);

        $announcement->delete();

        return back()->with('success', 'Announcement deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'description' => ['required', 'string', 'max:2000'],
        ]);
    }
}

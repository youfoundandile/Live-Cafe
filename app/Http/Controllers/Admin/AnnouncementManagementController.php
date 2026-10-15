<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Running\AnnouncementRequest;
use App\Models\Announcement;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

// Uses the run club's AnnouncementRequest, so a title is required and "publish" or "draft" decides who sees it
class AnnouncementManagementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::with('event')->forDisplay()->paginate(20);
        $events = $this->linkableEvents(new Announcement);

        return view('admin.announcements.index', compact('announcements', 'events'));
    }

    // The "new" form sits at the top of the list page
    public function create(): RedirectResponse
    {
        return redirect()->route('admin.announcements.index');
    }

    public function store(AnnouncementRequest $request): RedirectResponse
    {
        Gate::authorize('create', Announcement::class);

        $announcement = new Announcement($request->announcementAttributes());
        $announcement->user_id = $request->user()?->id;
        $announcement->published_at = $request->publishing() ? now() : null;
        $announcement->save();

        return redirect()->route('admin.announcements.index')
            ->with('success', $request->publishing() ? 'Announcement published.' : 'Announcement saved as a draft.');
    }

    public function show(Announcement $announcement): RedirectResponse
    {
        return redirect()->route('admin.announcements.edit', $announcement);
    }

    public function edit(Announcement $announcement): View
    {
        Gate::authorize('update', $announcement);

        $events = $this->linkableEvents($announcement);

        return view('admin.announcements.edit', compact('announcement', 'events'));
    }

    public function update(AnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        Gate::authorize('update', $announcement);

        $announcement->fill($request->announcementAttributes());

        if (! $request->publishing()) {
            $announcement->published_at = null;
        } elseif ($announcement->published_at === null) {
            $announcement->published_at = now();   // an already-published one keeps its original date
        }

        $announcement->save();

        return redirect()->route('admin.announcements.index')
            ->with('success', $request->publishing() ? 'Announcement saved and published.' : 'Announcement saved as a draft.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        Gate::authorize('delete', $announcement);

        $announcement->delete();

        return back()->with('success', 'Announcement deleted.');
    }

    /**
     * Upcoming runs and events, plus the one this announcement already links to if that has passed.
     *
     * @return Collection<int, Event>
     */
    private function linkableEvents(Announcement $announcement): Collection
    {
        $events = Event::upcoming()->chronological()->get();

        if ($announcement->event_id !== null && ! $events->contains('id', $announcement->event_id)) {
            $linked = Event::find($announcement->event_id);

            if ($linked !== null) {
                $events->prepend($linked);
            }
        }

        return $events;
    }
}

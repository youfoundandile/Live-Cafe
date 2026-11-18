<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Grab a handful of available products for the home page strip
        $featuredProducts = Product::where('prod_availability', true)
            ->where('quantity', '>', 0)
            ->whereHas('category', function ($query) {
                $query->whereIn('name', ['Food', 'Drink']);
            })
            ->with('category')
            ->latest()
            ->take(10)
            ->get();

        // Upcoming events only — ordered by soonest first (cancelled runs are left out)
        $upcomingEvents = Event::upcoming()
            ->scheduled()
            ->chronological()
            ->take(4)
            ->get();

        // Newest published announcement for the "What we offer" card (drafts and scheduled ones are left out)
        $latestAnnouncement = Announcement::published()
            ->where('published_at', '>=', now()->subDays(10))
            ->latest('published_at')
            ->latest('id')
            ->first();

        return view('home', compact('featuredProducts', 'upcomingEvents', 'latestAnnouncement'));
    }
}

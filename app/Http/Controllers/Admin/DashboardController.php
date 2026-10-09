<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Product;
use App\Models\Rsvp;
use App\Models\Sale;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalProducts' => Product::count(),
            'availableProducts' => Product::where('prod_availability', true)
                ->where('quantity', '>', 0)
                ->count(),
            'ordersToday' => Order::whereDate('created_at', today())
                ->whereIn('status', ['pending', 'confirmed'])
                ->count(),
            'revenueToday' => Sale::whereDate('occurred_at', today())
                ->where('status', 'confirmed')     // was 'collected', an order status, so always R0
                ->sum('total'),
            'upcomingEvents' => Event::where('event_date', '>=', now()->toDateString())
                ->where('event_date', '<=', now()->addDays(30)->toDateString())
                ->count(),
            'totalRsvps' => Rsvp::whereHas('event', fn ($q) => $q->where('event_date', '>=', now()->toDateString())
            )->count(),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->take(8)
            ->get();

        $recentSales = Sale::with('user')
            ->latest('occurred_at')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentSales'));
    }
}

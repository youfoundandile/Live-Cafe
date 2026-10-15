<?php

// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Product;
use App\Models\Rsvp;
use App\Models\Sale;
use App\Models\User;
use App\Services\Admin\DashboardStatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(DashboardStatsService $statsService): View
    {
        $stats = $statsService->get() + [
            'totalUsers' => User::count(),
            'totalProducts' => Product::count(),
            'availableProducts' => Product::with('ingredients')
                ->where('prod_availability', true)
                ->get()
                ->filter(fn (Product $product) => $product->availableQuantity() > 0)   // made items count from ingredients
                ->count(),
            'activeUsers' => DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', now()->subMinutes(15)->getTimestamp())
                ->distinct()
                ->count('user_id'),
            'ordersToday' => Order::whereDate('created_at', today())
                ->whereIn('status', ['pending', 'confirmed'])
                ->count(),
            'upcomingEvents' => Event::where('event_date', '>=', now()->toDateString())
                ->where('event_date', '<=', now()->addDays(30)->toDateString())
                ->count(),
            'totalRsvps' => Rsvp::active()->whereHas('event', fn ($q) => $q->where('event_date', '>=', now()->toDateString())
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

        $recentRsvps = Rsvp::active()->with(['user', 'event'])->latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentSales', 'recentRsvps'));
    }

    public function stats(DashboardStatsService $statsService): JsonResponse
    {
        return response()->json($statsService->get());
    }
}

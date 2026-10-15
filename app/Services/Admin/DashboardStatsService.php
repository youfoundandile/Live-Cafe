<?php

namespace App\Services\Admin;

use App\Models\Event;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Sale;
use App\Models\SyncConflict;
use Illuminate\Support\Carbon;

class DashboardStatsService
{
    public function get(): array
    {
        $orders = Order::whereDate('created_at', today())
            ->whereIn('status', ['confirmed', 'collected']);

        $sales = Sale::whereDate('occurred_at', today())
            ->where('status', 'confirmed');                  // cancelled = voided, excluded

        $nextEvent = Event::scheduled()->whereDate('event_date', '>=', today())
            ->withCount(['rsvps' => fn ($q) => $q->active()])
            ->withSum(['rsvps' => fn ($q) => $q->active()], 'extras')
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->first();

        return [
            'online_revenue' => (float) (clone $orders)->sum('total'),
            'pos_revenue' => (float) (clone $sales)->sum('total'),
            'orders_today' => (clone $orders)->count(),
            'sales_today' => (clone $sales)->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'low_stock' => Ingredient::whereColumn('quantity', '<=', 'reorder_level')->count(),
            'open_conflicts' => SyncConflict::open()->count(),
            'unsynced_note' => Sale::where('is_offline', true)->whereNull('synced_at')->count(),
            'next_event' => $nextEvent ? [
                'date' => Carbon::parse($nextEvent->event_date)->format('D j M'),
                'time' => substr($nextEvent->event_time, 0, 5),
                'expected' => $nextEvent->rsvps_count + (int) $nextEvent->getAttribute('rsvps_sum_extras'),
            ] : null,
            'updated_at' => now()->format('H:i:s'),
        ];
    }
}

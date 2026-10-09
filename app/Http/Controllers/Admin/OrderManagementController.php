<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\Inventory\StockService;          // use the namespace Andile agreed
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Order::class);

        $orders = Order::with('user')
            ->withCount('orderDetails')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->date, fn ($q, $d) => $q->whereDate('collection_time', $d))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        Gate::authorize('view', $order);

        $order->load(['user', 'orderDetails.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order, StockService $stock)
    {
        Gate::authorize('update', $order);

        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::TRANSITIONS))],
        ]);

        if (! $order->canMoveTo($data['status'])) {
            return back()->with('error', "Can't move an order from {$order->status} to {$data['status']}.");
        }

        DB::transaction(function () use ($order, $data, $stock) {
            if ($data['status'] === 'cancelled') {
                foreach (OrderDetail::with('product')->where('order_id', $order->id)->get() as $line) {
                    $stock->restore($line->product, $line->quantity, 'order_cancelled', $order);
                }
            }

            $order->update(['status' => $data['status']]);
        });

        return back()->with('success', "Order #{$order->id} marked {$data['status']}.");
    }
}

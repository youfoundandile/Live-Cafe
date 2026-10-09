<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * List the logged-in customer's orders, newest first.
     */
    public function index(): View
    {
        $orders = Order::where('user_id', auth()->id())
            ->withCount('orderDetails')
            ->latest()
            ->paginate(10);

        return view('shop.orders.index', compact('orders'));
    }

    /**
     * Show one order belonging to the logged-in customer.
     */
    public function show(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('orderDetails.product');

        return view('shop.orders.show', compact('order'));
    }

    /**
     * Cancel an order that has not been collected yet
     * and return its stock to the shared product inventory.
     */
    public function cancel(Order $order): RedirectResponse
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if (! in_array($order->status, ['pending', 'confirmed'], true)) {
            return back()->with('error', 'This order can no longer be cancelled.');
        }

        DB::transaction(function () use ($order) {

            // Lock each product while its stock is restored, so no other
            // transaction can change the same stock at the same time.
            foreach ($order->orderDetails as $detail) {

                $product = Product::where('id', $detail->product_id)
                    ->lockForUpdate()
                    ->first();

                if (! $product) {
                    continue;
                }

                $product->quantity += $detail->quantity;
                $product->prod_availability = true;
                $product->save();
            }

            $order->update(['status' => 'cancelled']);
        });

        return redirect()
            ->route('shop.orders.show', $order)
            ->with('success', 'Order #'.$order->id.' has been cancelled.');
    }
}
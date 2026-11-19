<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        $products = Product::where('prod_availability', true)
            ->where('quantity', '>', 0)
            ->with('category')
            ->orderBy('name')
            ->get();

        $categories = $products->pluck('category')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values();

        return view('pos.index', compact('products', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'payment_method' => ['required', 'in:cash,card'],
            'items' => ['required', 'string'],
        ]);

        $items = json_decode($request->input('items'), true);

        if (empty($items)) {
            return back()->with('error', 'No items in the sale.');
        }

        try {
            DB::transaction(function () use ($request, $items) {
                $total = 0;
                $saleDetails = [];

                foreach ($items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                    if ($product->quantity < $item['quantity']) {
                        throw new \RuntimeException("Insufficient stock for {$product->name}.");
                    }

                    $lineTotal = $product->price * $item['quantity'];
                    $total += $lineTotal;

                    $saleDetails[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                    ];

                    $product->decrement('quantity', $item['quantity']);
                }

                /** @var User $user */
                $user = auth()->user();

                $sale = Sale::create([
                    'user_id' => $user->id,
                    'payment_method' => $request->input('payment_method'),
                    'total' => $total,
                    'status' => 'confirmed',
                    'is_offline' => false,
                    'occurred_at' => now(),
                ]);

                foreach ($saleDetails as $detail) {
                    $sale->salesDetails()->create($detail);
                }
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('pos.sales.index')->with('success', 'Sale processed successfully.');
    }

    public function sales(): View
    {
        $sales = Sale::with(['user', 'salesDetails.product'])
            ->latest('occurred_at')
            ->take(50)
            ->get();

        $pendingOrders = Order::with(['user', 'orderDetails.product'])
            ->whereIn('status', ['confirmed', 'pending'])
            ->latest('collection_time')
            ->take(50)
            ->get();

        return view('pos.sales', compact('sales', 'pendingOrders'));
    }

    public function show(Sale $sale): View
    {
        $sale->load('salesDetails.product', 'user');

        return view('pos.sales', compact('sale'));
    }

    public function collectOrder(Order $order): RedirectResponse
    {
        if ($order->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed orders can be marked as collected.');
        }

        $order->update(['status' => 'collected']);

        return back()->with('success', "Order #{$order->id} marked as collected.");
    }

    public function lookupOrder(Order $order): JsonResponse
    {
        if (! in_array($order->status, ['pending', 'confirmed'], true)) {
            return response()->json(['error' => "Order #{$order->id} has already been {$order->status}."]);
        }

        $order->load('user', 'orderDetails.product');

        /** @var User $customer */
        $customer = $order->user;

        return response()->json([
            'id' => $order->id,
            'customer' => $customer->name.' '.$customer->surname,
            'status' => $order->status,
            'total' => number_format($order->total, 2),
            'collection_time' => Carbon::parse($order->collection_time)->format('H:i, d M'),
            'items' => $order->orderDetails->map(function ($d) {
                /** @var mixed $d */

                /** @var Product $product */
                $product = $d->product;

                return [
                    'quantity' => $d->quantity,
                    'product' => $product->name,
                ];
            }),
        ]);
    }

    public function sync(): void {}
}

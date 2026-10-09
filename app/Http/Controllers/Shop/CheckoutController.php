<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page: cart summary + collection time form.
     */
    public function index(): View|RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('shop.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $products = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);

            if (! $product) {
                continue;
            }

            $lineTotal = $product->price * $quantity;

            $products[] = [
                'product' => $product,
                'quantity' => $quantity,
                'lineTotal' => $lineTotal,
            ];

            $total += $lineTotal;
        }

        return view('shop.checkout', compact('products', 'total'));
    }

    /**
     * Process checkout: safely verify stock, create the order, reduce stock.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'collection_time' => ['required', 'date', 'after:now'],
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('shop.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        try {
            $order = DB::transaction(function () use ($cart, $validated) {

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'status' => 'pending',
                    'total' => 0,
                    'collection_time' => $validated['collection_time'],
                ]);

                $total = 0;

                foreach ($cart as $productId => $quantity) {

                    $product = Product::where('id', $productId)
                        ->lockForUpdate()
                        ->first();

                    if (! $product) {
                        throw new \Exception('A product in your cart no longer exists.');
                    }

                    if (! $product->prod_availability || $product->quantity < $quantity) {
                        throw new \Exception(
                            "Sorry, {$product->name} no longer has enough stock. Please update your cart."
                        );
                    }

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $product->price,
                    ]);

                    $product->quantity -= $quantity;

                    if ($product->quantity <= 0) {
                        $product->prod_availability = false;
                    }

                    $product->save();

                    $total += $product->price * $quantity;
                }

                $order->update(['total' => $total]);

                return $order;
            });

        } catch (\Exception $e) {
            return redirect()
                ->route('shop.cart.index')
                ->with('error', $e->getMessage());
        }

        session()->forget('cart');

        return redirect()
            ->route('shop.orders.show', $order)
            ->with('success', 'Order placed! Your order reference is #'.$order->id);
    }
}
<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * Validate the checkout details, then send the customer to payment.
     *
     * No order is created and no stock is deducted here. The order is only
     * created after Paystack confirms the payment (see PaymentController).
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

        /*
         * Preliminary stock check only, to avoid sending the customer to
         * Paystack for a product that is already clearly unavailable.
         * The authoritative, lock-protected check happens in
         * PaymentController, when the order is actually created.
         */
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);

            if (! $product) {
                return redirect()
                    ->route('shop.cart.index')
                    ->with('error', 'One of the products in your cart is no longer available.');
            }

            if (! $product->prod_availability || $product->quantity < $quantity) {
                return redirect()
                    ->route('shop.cart.index')
                    ->with('error', "Sorry, {$product->name} no longer has enough stock. Please update your cart.");
            }
        }

        // Keep the pickup time until payment is confirmed.
        session(['pending_collection_time' => $validated['collection_time']]);

        return redirect()->route('shop.payment.start');
    }
}

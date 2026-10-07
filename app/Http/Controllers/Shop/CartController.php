<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the customer's cart.
     */
    public function index(): View
    {
        $cart = session('cart', []);
        $products = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);

            // Ignore products that no longer exist.
            if (!$product) {
                continue;
            }

            // Ignore invalid cart quantities.
            if ($quantity < 1) {
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

        return view('shop.cart', compact('products', 'total'));
    }

    /**
     * Add one product to the cart.
     */
    public function add(Request $request, Product $product): RedirectResponse
    {
        if (!$product->prod_availability || $product->quantity < 1) {
            return back()->with(
                'error',
                'That product is currently unavailable.'
            );
        }

        $cart = session('cart', []);
        $currentQuantity = $cart[$product->id] ?? 0;

        // Do not allow the cart quantity to exceed available stock.
        if ($currentQuantity >= $product->quantity) {
            return back()->with(
                'error',
                'You cannot add more of this product because there is not enough stock.'
            );
        }

        $cart[$product->id] = $currentQuantity + 1;

        session(['cart' => $cart]);

        return back()->with(
            'success',
            $product->name . ' added to your cart.'
        );
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if (!$product->prod_availability || $product->quantity < 1) {
            return back()->with(
                'error',
                'That product is currently unavailable.'
            );
        }

        if ($validated['quantity'] > $product->quantity) {
            return back()->with(
                'error',
                'There is not enough stock available for that quantity.'
            );
        }

        $cart = session('cart', []);

        if (!isset($cart[$product->id])) {
            return back()->with(
                'error',
                'That product is not in your cart.'
            );
        }

        $cart[$product->id] = $validated['quantity'];

        session(['cart' => $cart]);

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove one product from the cart.
     */
    public function remove(Product $product): RedirectResponse
    {
        $cart = session('cart', []);

        unset($cart[$product->id]);

        session(['cart' => $cart]);

        return back()->with(
            'success',
            'Item removed from your cart.'
        );
    }

    /**
     * Remove all products from the cart.
     */
    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return back()->with(
            'success',
            'Your cart has been cleared.'
        );
    }
}
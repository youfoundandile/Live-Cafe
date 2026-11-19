<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
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

            if (! $product || $quantity < 1) {
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
     * Add one item to the cart.
     */
    public function add(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        if (! $product->prod_availability || $product->quantity < 1) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'That product is currently unavailable.',
                ], 409);
            }

            return back()->with('error', 'That product is currently unavailable.');
        }

        $cart = session('cart', []);
        $currentQuantity = $cart[$product->id] ?? 0;

        if ($currentQuantity >= $product->quantity) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'There is not enough stock available.',
                ], 409);
            }

            return back()->with('error', 'You cannot add more of this product because there is not enough stock.');
        }

        $cart[$product->id] = $currentQuantity + 1;
        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $product->name.' added to your cart.',
                'quantity' => $cart[$product->id],
                'cart_count' => array_sum($cart),
            ]);
        }

        return back()->with('success', $product->name.' added to your cart.');
    }

    /**
     * Update an item's quantity.
     * A quantity of zero removes the item.
     */
    public function update(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cart = session('cart', []);

        if (! isset($cart[$product->id])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'That product is not in your cart.',
                ], 404);
            }

            return back()->with('error', 'That product is not in your cart.');
        }

        $newQuantity = (int) $validated['quantity'];

        // Allow customers to remove an item even if it is now unavailable.
        if ($newQuantity === 0) {
            unset($cart[$product->id]);
            session(['cart' => $cart]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Item removed from your cart.',
                    'quantity' => 0,
                    'cart_count' => array_sum($cart),
                ]);
            }

            return back()->with('success', 'Item removed from your cart.');
        }

        if (! $product->prod_availability || $product->quantity < 1) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'That product is currently unavailable.',
                ], 409);
            }

            return back()->with('error', 'That product is currently unavailable.');
        }

        if ($newQuantity > $product->quantity) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'There is not enough stock available for that quantity.',
                ], 409);
            }

            return back()->with('error', 'There is not enough stock available for that quantity.');
        }

        $cart[$product->id] = $newQuantity;
        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Cart updated.',
                'quantity' => $newQuantity,
                'cart_count' => array_sum($cart),
            ]);
        }

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Product $product): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Item removed from your cart.');
    }

    /**
     * Clear the cart.
     */
    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return back()->with('success', 'Your cart has been cleared.');
    }
}

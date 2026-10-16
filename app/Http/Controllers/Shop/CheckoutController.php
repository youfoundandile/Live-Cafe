<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
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
            $product = Product::with('images')->find($productId);

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
            'collection_time' => [
                'required',
                'date_format:Y-m-d\TH:i',
            ],
        ]);

        try {
            $collectionTime = Carbon::createFromFormat(
                'Y-m-d\TH:i',
                $validated['collection_time'],
                config('app.timezone')
            );
        } catch (\Throwable $exception) {
            return back()
                ->withErrors([
                    'collection_time' => 'Please select a valid collection date and time.',
                ])
                ->withInput();
        }

        if (! $collectionTime || $collectionTime->lte(now())) {
            return back()
                ->withErrors([
                    'collection_time' => 'Please select a future collection time.',
                ])
                ->withInput();
        }

        $day = $collectionTime->dayOfWeekIso; // Monday = 1, Sunday = 7
        $time = $collectionTime->format('H:i');

        if ($day === 7) {
            return back()
                ->withErrors([
                    'collection_time' => 'Live Cafe is closed on Sundays. Please choose another day.',
                ])
                ->withInput();
        }

        $latestTime = $day === 6 ? '14:50' : '17:50';

        if (
            $time < '06:10' ||
            $time > $latestTime ||
            ((int) $collectionTime->format('i')) % 10 !== 0
        ) {
            return back()
                ->withErrors([
                    'collection_time' => 'Please select a collection slot during our operating hours: weekdays 06:10–17:50 or Saturdays 06:10–14:50, every 10 minutes.',
                ])
                ->withInput();
        }

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('shop.cart.index')
                ->with('error', 'Your cart is empty.');
        }

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

        session([
            'pending_collection_time' => $collectionTime->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('shop.payment.start');
    }
}

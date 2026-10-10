<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Start a Paystack payment for the current cart.
     */
    public function initialize(): RedirectResponse
    {
        abort_unless(auth()->user()->isCustomer(), 403);

        $cart = session('cart', []);
        $collectionTime = session('pending_collection_time');

        if (empty($cart) || ! $collectionTime) {
            return redirect()
                ->route('shop.cart.index')
                ->with('error', 'Your checkout session has expired. Please try again.');
        }

        // The amount is calculated here on the server from live prices.
        // We never trust an amount sent by the browser.
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);

            if (! $product) {
                return redirect()
                    ->route('shop.cart.index')
                    ->with('error', 'A product in your cart is no longer available.');
            }

            $total += (float) $product->price * $quantity;
        }

        $amountInCents = (int) round($total * 100);
        $reference = 'LC-'.auth()->id().'-'.Str::upper(Str::random(12));

        // Remember what we expect, so the callback can check it.
        session(['pending_payment' => [
            'reference' => $reference,
            'amount' => $amountInCents,
            'collection_time' => $collectionTime,
        ]]);

        $response = Http::withToken(config('services.paystack.secret'))
            ->post(config('services.paystack.url').'/transaction/initialize', [
                'email' => auth()->user()->email,
                'amount' => $amountInCents,
                'currency' => 'ZAR',
                'reference' => $reference,
                'callback_url' => route('shop.payment.callback'),
                'metadata' => [
                    'cancel_action' => route('shop.payment.cancelled'),
                ],
            ]);

        $paymentUrl = $response->json('data.authorization_url');

        if ($response->successful() && $paymentUrl) {
            return redirect()->away($paymentUrl);
        }

        Log::error('Paystack initialize failed', ['body' => $response->body()]);

        return redirect()
            ->route('shop.checkout.index')
            ->with('error', 'We could not start the payment. Please try again.');
    }

    /**
     * Paystack sends the customer back here. Verify the payment,
     * then create the order and reduce stock inside a locked transaction.
     */
    public function callback(Request $request): RedirectResponse
    {
        $pending = session('pending_payment');
        $reference = $request->query('reference');

        // Only accept the payment that we started in this session.
        if (! $pending || $pending['reference'] !== $reference) {
            return redirect()
                ->route('shop.cart.index')
                ->with('error', 'No pending payment was found.');
        }

        $response = Http::withToken(config('services.paystack.secret'))
            ->get(config('services.paystack.url')."/transaction/verify/{$reference}");

        $data = $response->json('data') ?? [];

        $paid = $response->successful()
            && ($data['status'] ?? null) === 'success'
            && (int) ($data['amount'] ?? 0) === $pending['amount']
            && ($data['currency'] ?? null) === 'ZAR';

        if (! $paid) {
            return redirect()
                ->route('shop.checkout.index')
                ->with('error', 'The payment was not completed. Your cart is unchanged.');
        }

        $cart = session('cart', []);

        try {
            $order = DB::transaction(function () use ($cart, $pending) {

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'status' => 'confirmed',   // paid, waiting for collection
                    'total' => 0,
                    'collection_time' => $pending['collection_time'],
                ]);

                $total = 0;

                foreach ($cart as $productId => $quantity) {

                    // Lock the row so nobody else can change this stock right now.
                    $product = Product::where('id', $productId)
                        ->lockForUpdate()
                        ->first();

                    if (! $product
                        || ! $product->prod_availability
                        || $product->quantity < $quantity) {
                        throw new \RuntimeException(
                            'An item sold out while you were paying.'
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

                    $total += (float) $product->price * $quantity;
                }

                // The cart total must still equal what was actually paid.
                if ((int) round($total * 100) !== $pending['amount']) {
                    throw new \RuntimeException(
                        'A price changed while you were paying.'
                    );
                }

                $order->update(['total' => $total]);

                return $order;
            });

        } catch (\RuntimeException $e) {
            // The customer paid but we cannot fulfil the order.
            // Nothing was saved, because the transaction rolled back.
            Log::warning('Paid but order failed', [
                'reference' => $reference,
                'reason' => $e->getMessage(),
            ]);

            session()->forget('pending_payment');

            return redirect()
                ->route('shop.cart.index')
                ->with('error', $e->getMessage()
                    .' Your payment will need to be refunded. Please contact Live Cafe and quote reference '
                    .$reference.'.');
        }

        session()->forget(['cart', 'pending_payment', 'pending_collection_time']);

        return redirect()
            ->route('shop.orders.show', $order)
            ->with('success', 'Payment successful! Your order #'.$order->id.' is confirmed.');
    }

    /**
     * The customer pressed Cancel on the Paystack page.
     */
    public function cancelled(): RedirectResponse
    {
        session()->forget('pending_payment');

        return redirect()
            ->route('shop.checkout.index')
            ->with('error', 'The payment was cancelled. Your cart is unchanged.');
    }
}

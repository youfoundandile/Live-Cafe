<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use RuntimeException;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $this->createOrder(
            'sarah@livecafe.test',
            'collected',
            now()->subDays(2)->setTime(8, 15),
            [
                'Latte (350ml)' => 2,
                'Butter Croissant' => 1,
            ]
        );

        $this->createOrder(
            'thando@livecafe.test',
            'collected',
            now()->subDay()->setTime(9, 20),
            [
                'Chicken Wrap' => 1,
                'Iced Latte' => 1,
            ]
        );

        $this->createOrder(
            'lwazi@livecafe.test',
            'confirmed',
            now()->addDay()->setTime(11, 30),
            [
                'Cappuccino (250ml)' => 1,
                'Banana Loaf' => 2,
            ]
        );

        $this->createOrder(
            'aphiwe@livecafe.test',
            'pending',
            now()->addHours(4),
            [
                'Americano (350ml)' => 1,
                'Green Salad' => 1,
            ]
        );

        $this->createOrder(
            'kamo@livecafe.test',
            'cancelled',
            now()->addDay()->setTime(13, 0),
            [
                'Pescado' => 1,
                'Homemade Lemonade' => 1,
            ]
        );

        $this->createOrder(
            'zanele@livecafe.test',
            'confirmed',
            now()->addHours(6),
            [
                'Club Anthem' => 1,
                'Still Water' => 2,
            ]
        );
    }

    private function createOrder(
        string $email,
        string $status,
        CarbonImmutable $collectionTime,
        array $items
    ): void {
        $user = User::where('email', $email)->firstOrFail();

        $order = Order::create([
            'user_id' => $user->id,
            'status' => $status,
            'total' => 0,
            'collection_time' => $collectionTime,
        ]);

        $total = 0;

        foreach ($items as $productName => $quantity) {
            $product = Product::where(
                'name',
                $productName
            )->firstOrFail();

            $unitPrice = (float) $product->price;

            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ]);

            $total += $unitPrice * $quantity;

            if (in_array($status, ['confirmed', 'collected'], true)) {
                if ($product->quantity < $quantity) {
                    throw new RuntimeException(
                        "Insufficient seed stock for {$product->name}."
                    );
                }

                $product->decrement('quantity', $quantity);
            }
        }

        $order->update([
            'total' => $total,
        ]);
    }
}
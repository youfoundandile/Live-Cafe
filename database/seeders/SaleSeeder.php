<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use RuntimeException;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $this->createSale(
            'staff1@livecafe.test',
            'confirmed',
            'cash',
            now()->setTime(8, 10),
            false,
            null,
            [
                'Latte (350ml)' => 1,
                'Butter Croissant' => 1,
            ]
        );

        $this->createSale(
            'staff2@livecafe.test',
            'confirmed',
            'card',
            now()->setTime(9, 0),
            false,
            null,
            [
                'Chicken Wrap' => 2,
                'Still Water' => 1,
            ]
        );

        $this->createSale(
            'staff1@livecafe.test',
            'confirmed',
            'cash',
            now()->subDay()->setTime(10, 30),
            false,
            null,
            [
                'Americano (250ml)' => 2,
                'Banana Loaf' => 1,
            ]
        );

        $this->createSale(
            'staff2@livecafe.test',
            'confirmed',
            'card',
            now()->subDay()->setTime(11, 15),
            false,
            null,
            [
                'Club Anthem' => 1,
                'Iced Matcha' => 1,
            ]
        );

        $this->createSale(
            'staff1@livecafe.test',
            'confirmed',
            'card',
            now()->subHours(2),
            true,
            now(),
            [
                'Iced Latte' => 2,
                'Extra Espresso Shot' => 1,
            ]
        );
    }

    private function createSale(
        string $staffEmail,
        string $status,
        string $paymentMethod,
        CarbonImmutable $occurredAt,
        bool $isOffline,
        ?CarbonImmutable $syncedAt,
        array $items
    ): void {
        $staff = User::where('email', $staffEmail)
            ->firstOrFail();

        $sale = Sale::create([
            'user_id' => $staff->id,
            'status' => $status,
            'payment_method' => $paymentMethod,
            'total' => 0,
            'is_offline' => $isOffline,
            'synced_at' => $syncedAt,
            'occurred_at' => $occurredAt,
        ]);

        $total = 0;

        foreach ($items as $productName => $quantity) {
            $product = Product::where(
                'name',
                $productName
            )->firstOrFail();

            $unitPrice = (float) $product->price;

            SalesDetail::create([
                'sales_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ]);

            $total += $unitPrice * $quantity;

            if ($status === 'confirmed') {
                if ($product->quantity < $quantity) {
                    throw new RuntimeException(
                        "Insufficient seed stock for {$product->name}."
                    );
                }

                $product->decrement('quantity', $quantity);
            }
        }

        $sale->update([
            'total' => $total,
        ]);
    }
}
<?php

namespace App\Services\Inventory;

use App\Exceptions\InsufficientStockException;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * online orders and live POS sales. All or nothing
     */
    public function deduct(Product $product, float $qty, string $reason, $source = null): void
    {
        //
        DB::transaction(function () use ($product, $qty, $reason, $source) {
            $rows = $this->rowsFor($product, $qty);
            foreach ($rows as [$row, $amount]) {
                // Checks everything
                if ($row->quantity < $amount) {
                    throw new InsufficientStockException($row);
                }
            }
            foreach ($rows as [$row, $amount]) {
                $row->decrement('quantity', $amount);
                $this->log($row, -$amount, $reason, $source);
            }
            $this->refreshAvailability($product);
        });

    }

    /* Cancels and voids puts stock back */

    public function restore(Product $product, float $qty, string $reason, $source = null): void
    {
        DB::transaction(function () use ($product, $qty, $reason, $source) {
            foreach ($this->rowsFor($product, $qty) as [$row, $amount]) {

                $row->increment('quantity', $amount);
                $this->log($row, $amount, $reason, $source);
            }

            $this->refreshAvailability($product);
        });
    }

    /* Offline POS sales : already paid, so never refuse. Stops at zero and returns the shorfall */
    public function deductForSync(Product $product, float $qty, Model $source): array
    {
        return DB::transaction(function () use ($product, $qty, $source) {

            $shortfalls = [];

            foreach ($this->rowsFor($product, $qty) as [$row, $amount]) {
                $take = min($row->quantity, $amount);
                if ($take > 0) {
                    $row->decrement('quantity', $take);
                    $this->log($row, -$take, 'Offline POS sale', $source);
                }
                if ($amount > $take) {
                    $shortfalls[] = [$amount - $take];
                }
            }
            $this->refreshAvailability($product);

            return $shortfalls;
        });
    }

    /* which rows to change, locked so two tills can't sell the last */
    private function rowsFor(Product $product, float $qty): array
    {
        $product->loadMissing('ingredients');

        if (! $product->isMade()) {
            return [[Product::lockForUpdate()->findorFail($product->id), $qty]];
        } else {
            return $product->ingredients->map(fn ($i) => [Ingredient::lockForUpdate()->FindOrFail($i->id), $qty * (float) $i->pivot->quantity_required])->all();

        }
    }

    private function refreshAvailability(Product $product): void
    {
        $product->refresh()->load('ingredients');

        foreach ($product->ingredients as $ingredient) {
            $ingredient->update(['availability' => $ingredient->quantity > 0]);
        }

        if ($product->availableQuantity() === 0) {
            $product->update(['prod_availability' => false]);
        }
    }

    private function log(Product|Ingredient $row, float $change, string $reason, ?Model $source): void
    {
        $row->inventoryTransactions()->create([
            'change' => $change,
            'reason' => $reason,
            'source_type' => $source?->getMorphClass(),
            'source_id' => $source?->getKey(),
            'user_id' => auth()->id(),
        ]);
    }
}

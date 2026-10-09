<?php

namespace App\Services\Admin;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SyncConflict;
use Illuminate\Support\Facades\DB;

class ReconciliationService
{
    /** Andile's sync() calls this with what deductForSync() returned. */
    public function record(Sale $sale, array $shortfalls): void
    {
        foreach ($shortfalls as $s) {
            SyncConflict::create([
                'sales_id' => $sale->id,
                'stockable_type' => $s['item']->getMorphClass(),
                'stockable_id' => $s['item']->getKey(),
                'shortfall' => $s['shortfall'],
            ]);
        }
    }

    /** Admin explains the shortfall. */
    public function resolve(SyncConflict $conflict, string $resolution, string $note, ?float $actualCount = null): void
    {
        DB::transaction(function () use ($conflict, $resolution, $note, $actualCount) {
            if ($resolution === 'recount') {
                /** @var Product|Ingredient $item */
                $item = $conflict->stockable()->lockForUpdate()->first();
                $diff = $actualCount - (float) $item->quantity;

                $item->update(['quantity' => $actualCount]);
                $item->inventoryTransactions()->create([
                    'change' => $diff, 'reason' => 'count_correction', 'user_id' => auth()->id(),
                ]);
            }

            $conflict->update([
                'status' => 'resolved',
                'resolution' => $resolution,
                'resolution_note' => $note,
                'resolved_by' => auth()->id(),
                'resolved_at' => now(),
            ]);
        });
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
    public function index()
    {
        return view('admin.inventory.index', [
            'ingredients' => Ingredient::orderBy('name')->get(),
            'counted' => Product::doesntHave('ingredients')->orderBy('name')->get(),   // merch
        ]);
    }

    public function adjust(Request $request, Product $product)
    {
        $this->apply($product, $this->validateAdjustment($request, 'integer'));   // merch is counted in whole items

        return back()->with('success', "{$product->name} stock updated.");
    }

    public function adjustIngredient(Request $request, Ingredient $ingredient)
    {
        $this->apply($ingredient, $this->validateAdjustment($request, 'numeric'));   // ingredients can be 0.5 kg

        return back()->with('success', "{$ingredient->name} stock updated.");
    }

    private function validateAdjustment(Request $request, string $type): array
    {
        return $request->validate([
            'change' => ['required', $type, 'not_in:0'],
            'reason' => ['required', 'in:delivery,wastage,count_correction'],
        ]);
    }

    private function apply($row, array $data): void
    {
        DB::transaction(function () use ($row, $data) {
            $row = $row::lockForUpdate()->findOrFail($row->id);

            if ($row->quantity + $data['change'] < 0) {
                throw ValidationException::withMessages(['change' => 'Stock cannot go below zero.']);
            }

            $row->increment('quantity', $data['change']);
            $row->inventoryTransactions()->create([
                'change' => $data['change'],
                'reason' => $data['reason'],
                'user_id' => auth()->id(),
            ]);
        });
    }
}

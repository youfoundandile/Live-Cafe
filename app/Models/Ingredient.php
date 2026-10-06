<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * An ingredient can be used by many products.
 *
 * @property-read ProductIngredient $pivot
 */
class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'availability',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'availability' => 'boolean',
        ];
    }

    /**
     * An ingredient can be used by many products.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_ingredients'
        )
            ->using(ProductIngredient::class)
            ->withPivot('quantity_required');
    }

    /**
     * Direct access to the product ingredient records.
     */
    public function productIngredients(): HasMany
    {
        return $this->hasMany(ProductIngredient::class);
    }

    /**
     * A ingredient can have many inventory transactions.
     */
    public function inventoryTransactions(): MorphMany
    {
        return $this->morphMany(InventoryTransaction::class, 'stockable');
    }
}

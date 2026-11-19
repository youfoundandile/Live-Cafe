<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'parent_id' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    // Products assigned to this category
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // The parent category (Food or Drinks)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Subcategories belonging to this category
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->orderBy('sort_order');
    }
}

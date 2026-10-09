<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'payment_method',
        'total',
        'is_offline',
        'synced_at',
        'occurred_at',
        'client_uuid',
        'voided_by',
        'voided_at',
        'void_reason',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'is_offline' => 'boolean',
            'synced_at' => 'datetime',
            'occurred_at' => 'datetime',
            'voided_at' => 'datetime',
        ];
    }

    /**
     * The staff user who processed the sale.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A sale contains many sales details.
     */
    public function salesDetails(): HasMany
    {
        return $this->hasMany(SalesDetail::class);
    }
}

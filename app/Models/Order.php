<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const TRANSITIONS = [
        /**
         * The available status transitions.
         */
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['collected', 'cancelled'],
        'collected' => [],
        'cancelled' => [],
    ];

    protected $fillable = [
        'user_id',
        'status',
        'total',
        'collection_time',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'collection_time' => 'datetime',
        ];
    }

    /**
     * An order belongs to one user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function canMoveTo(string $status): bool
    {
        return in_array($status, self::TRANSITIONS[$this->status] ?? [], true);

    }

    /**
     * An order contains many order details.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}

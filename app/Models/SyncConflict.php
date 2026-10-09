<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SyncConflict extends Model
{
    //
    protected $fillable = ['sales_id', 'stockable_type', 'stockable_id', 'shortfall', 'status',
        'resolution', 'resolution_note', 'resolved_by', 'resolved_at'];

    protected function casts(): array
    {
        return ['shortfall' => 'decimal:3', 'resolved_at' => 'datetime'];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sales_id');
    }

    public function stockable(): MorphTo
    {
        return $this->morphTo();
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}

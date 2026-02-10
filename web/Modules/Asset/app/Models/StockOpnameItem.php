<?php

namespace Modules\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOpnameItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_opname_id',
        'asset_id',
        'actual_status',
        'auditor_note',
    ];

    public function stockOpname(): BelongsTo
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Scope: Filter by actual status.
     */
    public function scopeActualStatus($query, string $status)
    {
        return $query->where('actual_status', $status);
    }

    /**
     * Scope: Filter items with issues.
     */
    public function scopeWithIssues($query)
    {
        return $query->whereIn('actual_status', ['MISSING', 'WRONG_LOCATION', 'DAMAGED']);
    }
}

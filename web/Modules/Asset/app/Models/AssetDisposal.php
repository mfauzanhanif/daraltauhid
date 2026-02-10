<?php

namespace Modules\Asset\Models;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetDisposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'asset_id',
        'approved_by_user_id',
        'disposal_date',
        'reason',
        'sale_price',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'disposal_date' => 'date',
            'sale_price' => 'decimal:2',
        ];
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Scope: Filter by reason.
     */
    public function scopeReason($query, string $reason)
    {
        return $query->where('reason', $reason);
    }
}

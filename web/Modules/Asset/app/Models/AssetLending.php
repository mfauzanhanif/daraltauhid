<?php

namespace Modules\Asset\Models;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetLending extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'asset_id',
        'borrower_user_id',
        'approved_by_user_id',
        'borrowed_at',
        'expected_return_at',
        'returned_at',
        'status',
        'purpose',
        'notes_condition_after',
    ];

    protected function casts(): array
    {
        return [
            'borrowed_at' => 'datetime',
            'expected_return_at' => 'datetime',
            'returned_at' => 'datetime',
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

    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'borrower_user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Scope: Filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'REQUESTED');
    }

    /**
     * Scope: Filter active loans.
     */
    public function scopeOnLoan($query)
    {
        return $query->where('status', 'ON_LOAN');
    }

    /**
     * Scope: Filter overdue loans.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'ON_LOAN')
                     ->where('expected_return_at', '<', now());
    }
}

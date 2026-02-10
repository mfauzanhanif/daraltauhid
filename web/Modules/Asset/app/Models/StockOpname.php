<?php

namespace Modules\Asset\Models;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockOpname extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'auditor_user_id',
        'title',
        'audit_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'audit_date' => 'date',
        ];
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditor_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(StockOpnameItem::class);
    }

    /**
     * Scope: Filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter draft audits.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'DRAFT');
    }

    /**
     * Scope: Filter completed audits.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }
}

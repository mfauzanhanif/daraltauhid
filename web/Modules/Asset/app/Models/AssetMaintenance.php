<?php

namespace Modules\Asset\Models;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AssetMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'asset_id',
        'reported_by_user_id',
        'ticket_number',
        'status',
        'issue_description',
        'evidence_photo_path',
        'repair_started_at',
        'repair_finished_at',
        'technician_name',
        'repair_cost',
        'repair_notes',
    ];

    protected function casts(): array
    {
        return [
            'repair_started_at' => 'datetime',
            'repair_finished_at' => 'datetime',
            'repair_cost' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($maintenance) {
            if (empty($maintenance->ticket_number)) {
                $maintenance->ticket_number = static::generateTicketNumber($maintenance);
            }
        });
    }

    /**
     * Generate unique ticket number: MNT/YEAR/SEQUENCE
     */
    public static function generateTicketNumber(AssetMaintenance $maintenance): string
    {
        $year = now()->format('Y');
        
        $lastTicket = static::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastTicket ? ((int) Str::afterLast($lastTicket->ticket_number, '/')) + 1 : 1;
        
        return sprintf('MNT/%s/%03d', $year, $sequence);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by_user_id');
    }

    /**
     * Scope: Filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter open tickets.
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['REPORTED', 'IN_REVIEW', 'IN_REPAIR']);
    }

    /**
     * Scope: Filter resolved tickets.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'RESOLVED');
    }
}

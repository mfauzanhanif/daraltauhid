<?php

namespace App\Models;

use App\Enums\FiscalPeriodStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FiscalPeriod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
            'status' => FiscalPeriodStatus::class,
        ];
    }

    /**
     * Scope a query to only include active fiscal period.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeStatus(Builder $query, FiscalPeriodStatus $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include open periods.
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', FiscalPeriodStatus::OPEN);
    }

    /**
     * Scope a query to only include closed periods.
     */
    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('status', FiscalPeriodStatus::CLOSED);
    }

    /**
     * Scope a query to only include audited periods.
     */
    public function scopeAudited(Builder $query): Builder
    {
        return $query->where('status', FiscalPeriodStatus::AUDITED);
    }

    /**
     * Get the current active fiscal period.
     */
    public static function current(): ?self
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Set this fiscal period as active (and deactivate others).
     */
    public function setAsActive(): void
    {
        DB::transaction(function () {
            // Lock semua row untuk mencegah race condition
            static::query()->lockForUpdate()->get();

            // Deactivate semua fiscal period lain
            static::where('id', '!=', $this->id)->update(['is_active' => false]);

            // Activate yang ini
            $this->update(['is_active' => true]);
        });
    }

    /**
     * Check if this period is open for transactions.
     */
    public function isOpen(): bool
    {
        return $this->status === FiscalPeriodStatus::OPEN;
    }

    /**
     * Check if this period is closed.
     */
    public function isClosed(): bool
    {
        return $this->status === FiscalPeriodStatus::CLOSED;
    }

    /**
     * Check if this period is audited (locked).
     */
    public function isAudited(): bool
    {
        return $this->status === FiscalPeriodStatus::AUDITED;
    }

    /**
     * Close this fiscal period.
     */
    public function close(): void
    {
        if ($this->status === FiscalPeriodStatus::OPEN) {
            $this->update(['status' => FiscalPeriodStatus::CLOSED]);
        }
    }

    /**
     * Reopen this fiscal period.
     */
    public function reopen(): void
    {
        if ($this->status === FiscalPeriodStatus::CLOSED) {
            $this->update(['status' => FiscalPeriodStatus::OPEN]);
        }
    }

    /**
     * Mark this fiscal period as audited (permanently locked).
     */
    public function markAsAudited(): void
    {
        $this->update(['status' => FiscalPeriodStatus::AUDITED]);
    }
}

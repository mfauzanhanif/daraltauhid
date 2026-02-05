<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        ];
    }

    /**
     * Scope a query to only include active fiscal period.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include open periods.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'OPEN');
    }

    /**
     * Scope a query to only include closed periods.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'CLOSED');
    }

    /**
     * Scope a query to only include audited periods.
     */
    public function scopeAudited($query)
    {
        return $query->where('status', 'AUDITED');
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
        // Deactivate all other fiscal periods
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate this one
        $this->update(['is_active' => true]);
    }

    /**
     * Check if this period is open for transactions.
     */
    public function isOpen(): bool
    {
        return $this->status === 'OPEN';
    }

    /**
     * Check if this period is closed.
     */
    public function isClosed(): bool
    {
        return $this->status === 'CLOSED';
    }

    /**
     * Check if this period is audited (locked).
     */
    public function isAudited(): bool
    {
        return $this->status === 'AUDITED';
    }

    /**
     * Close this fiscal period.
     */
    public function close(): void
    {
        if ($this->status === 'OPEN') {
            $this->update(['status' => 'CLOSED']);
        }
    }

    /**
     * Reopen this fiscal period.
     */
    public function reopen(): void
    {
        if ($this->status === 'CLOSED') {
            $this->update(['status' => 'OPEN']);
        }
    }

    /**
     * Mark this fiscal period as audited (permanently locked).
     */
    public function markAsAudited(): void
    {
        $this->update(['status' => 'AUDITED']);
    }
}

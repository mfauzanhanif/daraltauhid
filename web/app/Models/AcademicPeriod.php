<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AcademicPeriod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'academic_year_id',
        'name',
        'start_date',
        'end_date',
        'is_active',
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
     * Get the academic year that owns this period.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Scope a query to only include active academic period.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by semester name.
     */
    public function scopeSemester(Builder $query, string $name): Builder
    {
        return $query->where('name', $name);
    }

    /**
     * Get the current active academic period.
     */
    public static function current(): ?self
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Set this academic period as active (and deactivate others in the same year).
     */
    public function setAsActive(): void
    {
        DB::transaction(function () {
            // Lock semua period dalam tahun ajaran yang sama
            static::where('academic_year_id', $this->academic_year_id)
                ->lockForUpdate()
                ->get();

            // Deactivate semua period lain dalam tahun ajaran yang sama
            static::where('academic_year_id', $this->academic_year_id)
                ->where('id', '!=', $this->id)
                ->update(['is_active' => false]);

            // Activate yang ini
            $this->update(['is_active' => true]);
        });
    }
}

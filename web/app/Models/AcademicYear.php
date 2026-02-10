<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The "booted" method of the model.
     * Enforce constraint: hanya satu tahun ajaran yang aktif dalam satu waktu.
     */
    protected static function booted(): void
    {
        static::saving(function (AcademicYear $year) {
            if ($year->is_active) {
                // Nonaktifkan semua tahun ajaran lain yang aktif
                static::where('id', '!=', $year->id ?? 0)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }
        });
    }

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
     * Get the academic periods for this academic year.
     */
    public function academicPeriods(): HasMany
    {
        return $this->hasMany(AcademicPeriod::class);
    }

    /**
     * Scope a query to only include active academic year.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the current active academic year.
     */
    public static function current(): ?self
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Set this academic year as active (and deactivate others).
     */
    public function setAsActive(): void
    {
        // Deactivate all other academic years
        static::where('id', '!=', $this->id)->update(['is_active' => false]);

        // Activate this one
        $this->update(['is_active' => true]);
    }
}

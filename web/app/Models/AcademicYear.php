<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AcademicYear extends Model
{
    use HasFactory, SoftDeletes;

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
    public function scopeActive(Builder $query): Builder
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
        DB::transaction(function () {
            // Lock semua row untuk mencegah race condition
            static::query()->lockForUpdate()->get();

            // Deactivate semua tahun ajaran lain
            static::where('id', '!=', $this->id)->update(['is_active' => false]);

            // Activate yang ini
            $this->update(['is_active' => true]);
        });
    }
}

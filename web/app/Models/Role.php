<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'institution_id',
        'name',
        'guard_name',
    ];

    /**
     * Get the institution that owns this role.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Scope a query to filter by institution.
     */
    public function scopeForInstitution($query, int $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }

    /**
     * Scope a query to filter by institution code.
     */
    public function scopeForInstitutionCode($query, string $code)
    {
        return $query->whereHas('institution', function ($q) use ($code) {
            $q->where('code', $code);
        });
    }
}

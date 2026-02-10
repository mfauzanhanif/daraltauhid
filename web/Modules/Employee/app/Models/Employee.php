<?php

namespace Modules\Employee\Models;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nik',
        'nip',
        'nuptk',
        'npwp',
        'name',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'address',
        'phone',
        'email',
        'last_education',
        'major',
        'university',
        'bank_name',
        'bank_account',
        'bank_account_holder',
        'photo_path',
        'documents_path',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'documents_path' => 'array',
        ];
    }

    /**
     * Get the user account linked to this employee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all assignments for this employee.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(EmployeeAssignment::class);
    }

    /**
     * Get active assignments only.
     */
    public function activeAssignments(): HasMany
    {
        return $this->assignments()->where('is_active', true);
    }

    /**
     * Get institutions where this employee is assigned.
     */
    public function institutions()
    {
        return $this->belongsToMany(Institution::class, 'employee_assignments')
            ->withPivot(['position', 'employment_status', 'start_date', 'end_date', 'is_active', 'basic_salary', 'allowance_fixed'])
            ->withTimestamps();
    }

    /**
     * Scope: Filter by institution.
     */
    public function scopeInInstitution($query, int $institutionId)
    {
        return $query->whereHas('assignments', function ($q) use ($institutionId) {
            $q->where('institution_id', $institutionId)->where('is_active', true);
        });
    }

    /**
     * Scope: Filter active employees.
     */
    public function scopeActive($query)
    {
        return $query->whereHas('assignments', function ($q) {
            $q->where('is_active', true);
        });
    }
}

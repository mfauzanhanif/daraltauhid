<?php

namespace Modules\Employee\Models;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'institution_id',
        'position',
        'employment_status',
        'start_date',
        'end_date',
        'is_active',
        'basic_salary',
        'allowance_fixed',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
            'basic_salary' => 'decimal:2',
            'allowance_fixed' => 'decimal:2',
        ];
    }

    /**
     * Get the employee for this assignment.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the institution for this assignment.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Scope: Filter active assignments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by employment status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('employment_status', $status);
    }

    /**
     * Scope: Filter by institution.
     */
    public function scopeInInstitution($query, int $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    use HasRoles {
        assignRole as spatieAssignRole;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    // ========================================
    // INSTITUTION ACCESS METHODS
    // ========================================

    /**
     * Get all institutions this user has roles in.
     * Returns a collection of Institution models.
     */
    public function getInstitutions()
    {
        return Institution::whereIn(
            'id',
            $this->roles->pluck('institution_id')->unique()->filter()
        )->get();
    }

    /**
     * Get all institution IDs this user has roles in.
     * Returns array of institution IDs.
     */
    public function getInstitutionIds(): array
    {
        return $this->roles
            ->pluck('institution_id')
            ->unique()
            ->filter()
            ->values()
            ->toArray();
    }

    /**
     * Check if user has any role in a specific institution.
     */
    public function hasRoleInInstitution(int|Institution $institution): bool
    {
        $institutionId = $institution instanceof Institution
            ? $institution->id
            : $institution;

        return $this->roles()
            ->where('institution_id', $institutionId)
            ->exists();
    }

    /**
     * Get all roles for a specific institution.
     * If null is passed, returns global roles (roles without institution).
     */
    public function getRolesInInstitution(int|Institution|null $institution)
    {
        // Jika null, return global roles
        if ($institution === null) {
            return $this->roles()
                ->whereNull('institution_id')
                ->get();
        }

        $institutionId = $institution instanceof Institution
            ? $institution->id
            : $institution;

        return $this->roles()
            ->where('institution_id', $institutionId)
            ->get();
    }

    /**
     * Check if user has a specific role in a specific institution.
     */
    public function hasRoleInInstitutionByName(string $roleName, int|Institution $institution): bool
    {
        $institutionId = $institution instanceof Institution
            ? $institution->id
            : $institution;

        return $this->roles()
            ->where('institution_id', $institutionId)
            ->where('name', $roleName)
            ->exists();
    }

    /**
     * Check if user is a global admin (role without institution_id).
     */
    public function isGlobalAdmin(): bool
    {
        return $this->roles()
            ->whereNull('institution_id')
            ->exists();
    }

    /**
     * Get the primary institution for this user.
     * Returns the first institution or null.
     */
    public function getPrimaryInstitution(): ?Institution
    {
        $institutionId = $this->roles()
            ->whereNotNull('institution_id')
            ->first()
            ?->institution_id;

        return $institutionId ? Institution::find($institutionId) : null;
    }

    // ========================================
    // WALI SANTRI (STUDENT GUARDIAN) METHODS
    // ========================================

    /**
     * Check if user is a wali (guardian) of a specific student.
     *
     * @param  string  $studentPublicId  The student's public_id (NanoID)
     */
    public function isWaliOf(string $studentPublicId): bool
    {
        // @todo: Implement when Student model and student_guardians table are ready
        // return $this->students()
        //     ->where('public_id', $studentPublicId)
        //     ->exists();

        return false; // Placeholder
    }

    /**
     * Get all students this user is guardian of.
     */
    public function getStudents()
    {
        // @todo: Implement when Student model is ready
        // return $this->belongsToMany(Student::class, 'student_guardians')
        //     ->withPivot('relationship')
        //     ->get();

        return collect(); // Placeholder - return empty collection
    }

    /**
     * Check if user has wali santri role at any institution.
     */
    public function isWaliSantri(): bool
    {
        return $this->roles()
            ->where('name', 'Wali Santri')
            ->exists();
    }

    // ========================================
    // PORTAL ACCESS HELPERS
    // ========================================

    /**
     * Determine which portal types this user can access.
     * Used for portal selection after login.
     *
     * @return array List of available portal types
     */
    public function getAvailablePortals(): array
    {
        $portals = [];

        // Check for institution-scoped roles
        $institutions = $this->getInstitutions();
        if ($institutions->isNotEmpty()) {
            $portals['institutions'] = $institutions->map(fn (Institution $i) => [
                'id' => $i->id,
                'code' => $i->code,
                'name' => $i->name,
                'type' => $i->type,
                'url' => $i->getDashboardUrl(),
            ])->toArray();
        }

        // Check for wali santri access
        if ($this->isWaliSantri()) {
            $students = $this->getStudents();
            if ($students->isNotEmpty()) {
                $portals['students'] = $students->map(fn ($s) => [
                    'id' => $s->id ?? null,
                    'public_id' => $s->public_id ?? null,
                    'name' => $s->name ?? null,
                    'url' => url("/wali/{$s->public_id}/dashboard"),
                ])->toArray();
            }
        }

        // Check for global admin
        if ($this->isGlobalAdmin()) {
            $portals['admin'] = [
                'name' => 'Admin Yayasan',
                'url' => root_dashboard_url(),
            ];
        }

        return $portals;
    }

    /**
     * Check if user needs portal selection after login.
     * True if user has multiple institutions or is both employee and wali.
     */
    public function needsPortalSelection(): bool
    {
        $institutionCount = count($this->getInstitutionIds());
        $isWali = $this->isWaliSantri();
        $isGlobal = $this->isGlobalAdmin();

        // Needs selection if: multiple institutions, or is wali + has other roles
        return $institutionCount > 1
            || ($isWali && $institutionCount > 0)
            || ($isGlobal && $institutionCount > 0);
    }

    /**
     * Get default redirect URL after login.
     * For users with single access point.
     */
    public function getDefaultPortalUrl(): string
    {
        // Global admin goes to admin dashboard
        if ($this->isGlobalAdmin() && ! $this->hasRoleInAnyInstitution()) {
            return root_dashboard_url();
        }

        // Single institution user
        $institutions = $this->getInstitutions();
        if ($institutions->count() === 1) {
            return $institutions->first()->getDashboardUrl();
        }

        // Wali santri only
        if ($this->isWaliSantri() && $institutions->isEmpty()) {
            $students = $this->getStudents();
            if ($students->count() === 1) {
                return url("/wali/{$students->first()->public_id}/dashboard");
            }
        }

        // Default to institution selection
        return url('/select-institution');
    }

    /**
     * Override method asli Spatie untuk keamanan.
     * Mencegah penggunaan assignRole('nama_role') yang ambigu di sistem Multi-Tenancy.
     *
     * @param  mixed  ...$roles
     * @return $this
     */
    public function assignRole(...$roles)
    {
        foreach ($roles as $role) {
            // Jika input adalah string (nama role), LEMPAR ERROR.
            // Kita memaksa developer memakai assignRoleInInstitution agar jelas lembaga mana.
            if (is_string($role)) {
                throw new \InvalidArgumentException(
                    "DILARANG menggunakan assignRole('nama_role') karena ambigu (satu nama role bisa ada di banyak lembaga). ".
                    "Gunakan assignRoleInInstitution('nama_role', \$institution) atau pass object Role secara langsung."
                );
            }
        }

        // Jika input aman (Object Role), panggil method asli Spatie yang sudah di-rename
        return $this->spatieAssignRole(...$roles);
    }

    /**
     * Check if user has role in any institution.
     */
    protected function hasRoleInAnyInstitution(): bool
    {
        return $this->roles()
            ->whereNotNull('institution_id')
            ->exists();
    }

    /**
     * Assign a role to the user within a specific institution context.
     * Prevents ambiguity when roles have duplicate names across institutions.
     */
    public function assignRoleInInstitution(string $roleName, int|Institution $institution): self
    {
        $institutionId = $institution instanceof Institution ? $institution->id : $institution;

        $role = Role::where('name', $roleName)
            ->where('institution_id', $institutionId)
            ->firstOrFail();

        $this->assignRole($role);

        return $this;
    }

    /**
     * Remove a role from the user within a specific institution context.
     */
    public function removeRoleInInstitution(string $roleName, int|Institution $institution): self
    {
        $institutionId = $institution instanceof Institution ? $institution->id : $institution;

        $role = Role::where('name', $roleName)
            ->where('institution_id', $institutionId)
            ->first();

        if ($role) {
            $this->removeRole($role);
        }

        return $this;
    }
}

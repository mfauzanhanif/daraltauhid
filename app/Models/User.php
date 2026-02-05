<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

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
     */
    public function getRolesInInstitution(int|Institution $institution)
    {
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
}

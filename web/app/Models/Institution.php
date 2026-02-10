<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Institution extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The "booted" method of the model.
     * Cache invalidation untuk path-based tenancy (by code).
     */
    protected static function booted(): void
    {
        static::saved(function (Institution $institution) {
            // Hapus cache berdasarkan code lama dan baru
            if ($institution->isDirty('code')) {
                Cache::forget('institution_code_' . $institution->getOriginal('code'));
            }
            Cache::forget('institution_code_' . $institution->code);
            
            // Tetap hapus domain cache untuk landing pages
            if ($institution->isDirty('domain')) {
                Cache::forget('institution_domain_' . $institution->getOriginal('domain'));
            }
            Cache::forget('institution_domain_' . $institution->domain);
        });

        static::deleted(function (Institution $institution) {
            Cache::forget('institution_code_' . $institution->code);
            Cache::forget('institution_domain_' . $institution->domain);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'slug',
        'domain',
        'name',
        'nickname',
        'no_statistic',
        'npsn',
        'is_internal',
        'category',
        'type',
        'headmaster_id',
        'email',
        'phone',
        'website_url',
        'address',
        'district',
        'city',
        'logo_path',
        'letterhead_path',
        'parent_id',
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
            'is_internal' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * Get the parent institution (self-reference).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'parent_id');
    }

    /**
     * Get the headmaster (Kepala Lembaga) of this institution.
     */
    public function headmaster(): BelongsTo
    {
        return $this->belongsTo(\Modules\Employee\Models\Employee::class, 'headmaster_id');
    }

    /**
     * Get the child institutions.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Institution::class, 'parent_id');
    }

    /**
     * Get all roles for this institution.
     */
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    // ========================================
    // SCOPES
    // ========================================

    /**
     * Scope a query to only include active institutions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include internal institutions.
     */
    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include external institutions.
     */
    public function scopeExternal($query)
    {
        return $query->where('is_internal', false);
    }

    /**
     * Scope a query to filter by domain.
     */
    public function scopeByDomain($query, string $domain)
    {
        return $query->where('domain', $domain);
    }

    /**
     * Scope a query to filter by code.
     */
    public function scopeByCode($query, string $code)
    {
        return $query->where('code', $code);
    }

    // ========================================
    // STATIC FINDERS
    // ========================================

    /**
     * Find institution by code (for path-based multi-tenancy).
     * Used for URL routing: /MI/dashboard -> MI institution
     */
    public static function findByCode(string $code): ?self
    {
        return static::where('code', $code)
            ->where('is_active', true)
            ->where('is_internal', true) // Hanya internal yang bisa akses app
            ->first();
    }

    /**
     * Find institution by domain (for landing pages).
     * Used for domain mapping: mis.daraltauhid.com -> MI landing
     */
    public static function findByDomain(string $domain): ?self
    {
        return static::where('domain', $domain)
            ->where('is_active', true)
            ->first();
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Get the full hierarchy path (Yayasan > Pondok > MI).
     */
    public function getHierarchyPath(): string
    {
        $path = [$this->name];
        $current = $this;

        while ($current->parent) {
            $current = $current->parent;
            array_unshift($path, $current->name);
        }

        return implode(' > ', $path);
    }

    /**
     * Check if this institution is a root (Yayasan Pusat).
     */
    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Check if this institution has children.
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Get the URL path for this institution's dashboard.
     */
    public function getDashboardUrl(): string
    {
        return url("/{$this->code}/dashboard");
    }

    /**
     * Get route key name for route model binding.
     * This allows using {institution} in routes with code instead of id.
     */
    public function getRouteKeyName(): string
    {
        return 'code';
    }
}

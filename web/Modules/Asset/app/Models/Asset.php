<?php

namespace Modules\Asset\Models;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institution_id',
        'asset_category_id',
        'room_id',
        'name',
        'code',
        'brand',
        'model',
        'serial_number',
        'purchase_date',
        'purchase_price',
        'funding_source',
        'useful_life_years',
        'condition',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'purchase_price' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($asset) {
            if (empty($asset->code)) {
                $asset->code = static::generateCode($asset);
            }
        });
    }

    /**
     * Generate unique asset code: INV/YEAR/INST_CODE/SEQUENCE
     */
    public static function generateCode(Asset $asset): string
    {
        $year = now()->format('Y');
        $institution = Institution::find($asset->institution_id);
        $instCode = $institution ? $institution->code : 'XX';
        
        $lastAsset = static::where('institution_id', $asset->institution_id)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastAsset ? ((int) Str::afterLast($lastAsset->code, '/')) + 1 : 1;
        
        return sprintf('INV/%s/%s/%03d', $year, $instCode, $sequence);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function mutations(): HasMany
    {
        return $this->hasMany(AssetMutation::class);
    }

    public function lendings(): HasMany
    {
        return $this->hasMany(AssetLending::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class);
    }

    public function disposals(): HasMany
    {
        return $this->hasMany(AssetDisposal::class);
    }

    /**
     * Scope: Filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter active assets.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    /**
     * Scope: Filter by condition.
     */
    public function scopeCondition($query, string $condition)
    {
        return $query->where('condition', $condition);
    }

    /**
     * Scope: Filter by category.
     */
    public function scopeInCategory($query, int $categoryId)
    {
        return $query->where('asset_category_id', $categoryId);
    }

    /**
     * Scope: Filter by room.
     */
    public function scopeInRoom($query, int $roomId)
    {
        return $query->where('room_id', $roomId);
    }
}

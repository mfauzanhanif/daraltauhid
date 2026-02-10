<?php

namespace Modules\Asset\Models;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institution_id',
        'building_id',
        'name',
        'code',
        'floor_number',
        'capacity',
        'pic_user_id',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function picUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Scope: Filter by building.
     */
    public function scopeInBuilding($query, int $buildingId)
    {
        return $query->where('building_id', $buildingId);
    }
}

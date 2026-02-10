<?php

namespace Modules\Asset\Models;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'name',
        'is_depreciable',
    ];

    protected function casts(): array
    {
        return [
            'is_depreciable' => 'boolean',
        ];
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'adhesion_fee',
        'death_contribution',
        'min_age',
        'max_age',
        'is_active',
    ];

    protected $casts = [
        'adhesion_fee' => 'decimal:2',
        'death_contribution' => 'decimal:2',
        'min_age' => 'integer',
        'max_age' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec les membres
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Scope pour les types actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Vérifier si un âge est valide pour ce type de membre
     */
    public function isValidAge(int $age): bool
    {
        if ($this->min_age && $age < $this->min_age) {
            return false;
        }
        
        if ($this->max_age && $age > $this->max_age) {
            return false;
        }
        
        return true;
    }
}

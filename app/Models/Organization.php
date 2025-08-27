<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'business_number',
        'contact_person',
        'contact_email',
        'contact_phone',
        'address',
        'city',
        'province',
        'postal_code',
        'country',
        'member_count',
        'total_fees',
        'is_active',
    ];

    protected $casts = [
        'member_count' => 'integer',
        'total_fees' => 'decimal:2',
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
     * Scope pour les organisations actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Vérifier si l'organisation est active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Calculer le nombre total de membres
     */
    public function calculateMemberCount(): int
    {
        return $this->members()->active()->count();
    }

    /**
     * Calculer le total des frais
     */
    public function calculateTotalFees(): float
    {
        return $this->members()
            ->active()
            ->with('memberType')
            ->get()
            ->sum(function ($member) {
                return $member->memberType->adhesion_fee + $member->memberType->death_contribution;
            });
    }

    /**
     * Mettre à jour les statistiques
     */
    public function updateStatistics(): void
    {
        $this->update([
            'member_count' => $this->calculateMemberCount(),
            'total_fees' => $this->calculateTotalFees(),
        ]);
    }

    /**
     * Obtenir l'adresse complète
     */
    public function getFullAddressAttribute(): string
    {
        return $this->address . ', ' . $this->city . ', ' . $this->province . ' ' . $this->postal_code . ', ' . $this->country;
    }

    /**
     * Obtenir le total des frais formaté
     */
    public function getFormattedTotalFeesAttribute(): string
    {
        return number_format($this->total_fees, 2) . ' CAD';
    }

    /**
     * Boot method pour les événements
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($organization) {
            if ($organization->isDirty('member_count') || $organization->isDirty('total_fees')) {
                $organization->updateStatistics();
            }
        });
    }
}

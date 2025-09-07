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
     * Calculer le total des frais d'adhésion pour les associations
     * Utilise les paramètres de calcul dynamiques
     */
    public function calculateAdhesionFees(): float
    {
        $memberCount = $this->calculateMemberCount();
        $setting = \App\Models\OrganizationCalculationSetting::getActive();
        
        if ($setting) {
            return $setting->calculateAdhesionFees($memberCount);
        }
        
        // Fallback to hardcoded calculation
        return $memberCount * 50.00;
    }

    /**
     * Calculer le total des contributions pour les associations
     * Contribution = (contribution de la catégorie × nombre de membres selon la catégorie)
     */
    public function calculateContributionFees(): float
    {
        $members = $this->members()
            ->active()
            ->with('memberType')
            ->get();

        $totalContribution = 0;

        foreach ($members as $member) {
            $totalContribution += $member->memberType->death_contribution;
        }

        return $totalContribution;
    }

    /**
     * Calculer le total des frais (adhésion + contributions)
     */
    public function calculateTotalFees(): float
    {
        return $this->calculateAdhesionFees() + $this->calculateContributionFees();
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
     * Obtenir la répartition des frais par type de membre
     */
    public function getFeesBreakdown(): array
    {
        $members = $this->members()
            ->active()
            ->with('memberType')
            ->get();

        $breakdown = [
            'régulier' => ['count' => 0, 'adhesion' => 0, 'contribution' => 0],
            'senior' => ['count' => 0, 'adhesion' => 0, 'contribution' => 0],
            'junior' => ['count' => 0, 'adhesion' => 0, 'contribution' => 0],
            'association' => ['count' => 0, 'adhesion' => 0, 'contribution' => 0],
        ];

        foreach ($members as $member) {
            $type = $member->memberType->name;
            if (isset($breakdown[$type])) {
                $breakdown[$type]['count']++;
                $breakdown[$type]['adhesion'] += 50.00; // 50$ par membre
                $breakdown[$type]['contribution'] += $member->memberType->death_contribution;
            }
        }

        return $breakdown;
    }

    /**
     * Obtenir le résumé des frais
     */
    public function getFeesSummary(): array
    {
        $breakdown = $this->getFeesBreakdown();
        
        return [
            'total_members' => $this->calculateMemberCount(),
            'total_adhesion' => $this->calculateAdhesionFees(),
            'total_contribution' => $this->calculateContributionFees(),
            'total_fees' => $this->calculateTotalFees(),
            'breakdown' => $breakdown,
        ];
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

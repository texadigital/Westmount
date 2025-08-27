<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'current_balance',
        'total_contributions',
        'total_distributions',
        'type',
        'is_active',
    ];

    protected $casts = [
        'current_balance' => 'decimal:2',
        'total_contributions' => 'decimal:2',
        'total_distributions' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Scope pour les fonds actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les fonds par type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Vérifier si le fonds est actif
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Ajouter une contribution
     */
    public function addContribution(float $amount): void
    {
        $this->increment('total_contributions', $amount);
        $this->increment('current_balance', $amount);
    }

    /**
     * Ajouter une distribution
     */
    public function addDistribution(float $amount): void
    {
        if ($this->current_balance >= $amount) {
            $this->increment('total_distributions', $amount);
            $this->decrement('current_balance', $amount);
        }
    }

    /**
     * Vérifier si le fonds a suffisamment de fonds
     */
    public function hasSufficientFunds(float $amount): bool
    {
        return $this->current_balance >= $amount;
    }

    /**
     * Obtenir le solde formaté
     */
    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->current_balance, 2) . ' CAD';
    }

    /**
     * Obtenir le total des contributions formaté
     */
    public function getFormattedTotalContributionsAttribute(): string
    {
        return number_format($this->total_contributions, 2) . ' CAD';
    }

    /**
     * Obtenir le total des distributions formaté
     */
    public function getFormattedTotalDistributionsAttribute(): string
    {
        return number_format($this->total_distributions, 2) . ' CAD';
    }

    /**
     * Obtenir le type traduit
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'general' => 'Général',
            'death_benefit' => 'Prestation de décès',
            'emergency' => 'Urgence',
            default => ucfirst($this->type),
        };
    }

    /**
     * Obtenir le pourcentage d'utilisation
     */
    public function getUsagePercentageAttribute(): float
    {
        if ($this->total_contributions == 0) {
            return 0;
        }

        return ($this->total_distributions / $this->total_contributions) * 100;
    }

    /**
     * Boot method pour les événements
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($fund) {
            // S'assurer que le solde ne devient pas négatif
            if ($fund->current_balance < 0) {
                $fund->current_balance = 0;
            }
        });
    }
}

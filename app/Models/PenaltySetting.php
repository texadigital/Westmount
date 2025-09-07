<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenaltySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'penalty_amount',
        'penalty_type',
        'grace_period_days',
        'escalation_days',
        'escalation_multiplier',
        'is_active',
        'notification_schedule',
    ];

    protected $casts = [
        'penalty_amount' => 'decimal:2',
        'escalation_multiplier' => 'decimal:2',
        'is_active' => 'boolean',
        'notification_schedule' => 'array',
    ];

    /**
     * Scope pour les paramètres actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Calculer la pénalité pour un nombre de jours de retard
     */
    public function calculatePenalty(int $overdueDays, float $originalAmount = 0): float
    {
        if ($overdueDays <= $this->grace_period_days) {
            return 0;
        }

        $penaltyAmount = $this->penalty_amount;

        // Si c'est un pourcentage, calculer basé sur le montant original
        if ($this->penalty_type === 2) {
            $penaltyAmount = ($originalAmount * $this->penalty_amount) / 100;
        }

        // Appliquer l'escalation si nécessaire
        if ($overdueDays > $this->escalation_days) {
            $escalationFactor = ceil(($overdueDays - $this->escalation_days) / 30);
            $penaltyAmount *= pow($this->escalation_multiplier, $escalationFactor);
        }

        return round($penaltyAmount, 2);
    }

    /**
     * Vérifier si une notification doit être envoyée
     */
    public function shouldSendNotification(int $overdueDays): bool
    {
        if (!$this->notification_schedule) {
            return false;
        }

        return in_array($overdueDays, $this->notification_schedule);
    }

    /**
     * Obtenir le prochain jour de notification
     */
    public function getNextNotificationDay(int $overdueDays): ?int
    {
        if (!$this->notification_schedule) {
            return null;
        }

        $futureDays = array_filter($this->notification_schedule, function($day) use ($overdueDays) {
            return $day > $overdueDays;
        });

        return empty($futureDays) ? null : min($futureDays);
    }

    /**
     * Obtenir les paramètres par défaut pour un type de paiement
     */
    public static function getDefaultForType(string $type): ?self
    {
        $settingName = match($type) {
            'adhesion' => 'default_adhesion_penalty',
            'contribution' => 'default_contribution_penalty',
            default => 'default_adhesion_penalty',
        };

        return self::where('name', $settingName)->active()->first();
    }
}
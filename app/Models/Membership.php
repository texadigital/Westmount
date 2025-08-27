<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'status',
        'start_date',
        'end_date',
        'adhesion_fee_paid',
        'total_contributions_paid',
        'amount_due',
        'last_payment_date',
        'next_payment_due',
        'overdue_days',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'last_payment_date' => 'date',
        'next_payment_due' => 'date',
        'adhesion_fee_paid' => 'decimal:2',
        'total_contributions_paid' => 'decimal:2',
        'amount_due' => 'decimal:2',
        'overdue_days' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec le membre
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Relation avec les paiements
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relation avec les contributions
     */
    public function contributions(): HasMany
    {
        return $this->hasMany(Contribution::class);
    }

    /**
     * Scope pour les adhésions actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les adhésions en retard
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Scope pour les adhésions caduques
     */
    public function scopeLapsed($query)
    {
        return $query->where('status', 'lapsed');
    }

    /**
     * Vérifier si l'adhésion est en retard
     */
    public function isOverdue(): bool
    {
        return $this->status === 'overdue' || 
               ($this->next_payment_due && $this->next_payment_due->isPast());
    }

    /**
     * Vérifier si l'adhésion est caduque
     */
    public function isLapsed(): bool
    {
        return $this->status === 'lapsed';
    }

    /**
     * Calculer les jours de retard
     */
    public function calculateOverdueDays(): int
    {
        if (!$this->next_payment_due) {
            return 0;
        }

        return max(0, Carbon::now()->diffInDays($this->next_payment_due, false));
    }

    /**
     * Mettre à jour le statut de l'adhésion
     */
    public function updateStatus(): void
    {
        $overdueDays = $this->calculateOverdueDays();
        
        if ($overdueDays > 90) {
            $this->status = 'lapsed';
        } elseif ($overdueDays > 0) {
            $this->status = 'overdue';
        } else {
            $this->status = 'active';
        }
        
        $this->overdue_days = $overdueDays;
        $this->save();
    }

    /**
     * Calculer le montant total dû
     */
    public function calculateAmountDue(): float
    {
        $memberType = $this->member->memberType;
        $totalOwed = $memberType->adhesion_fee + $memberType->death_contribution;
        $totalPaid = $this->adhesion_fee_paid + $this->total_contributions_paid;
        
        return max(0, $totalOwed - $totalPaid);
    }

    /**
     * Boot method pour les événements
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($membership) {
            $membership->amount_due = $membership->calculateAmountDue();
        });
    }
}

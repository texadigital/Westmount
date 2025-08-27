<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'deceased_member_id',
        'amount',
        'status',
        'due_date',
        'paid_date',
        'payment_id',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    /**
     * Relation avec le membre qui paie
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Relation avec le membre décédé
     */
    public function deceasedMember(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'deceased_member_id');
    }

    /**
     * Relation avec le paiement
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Scope pour les contributions en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour les contributions payées
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope pour les contributions annulées
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope pour les contributions en retard
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', 'pending');
    }

    /**
     * Vérifier si la contribution est payée
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Vérifier si la contribution est en attente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Vérifier si la contribution est annulée
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Vérifier si la contribution est en retard
     */
    public function isOverdue(): bool
    {
        return $this->isPending() && $this->due_date->isPast();
    }

    /**
     * Marquer la contribution comme payée
     */
    public function markAsPaid(Payment $payment = null): void
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now(),
            'payment_id' => $payment?->id,
        ]);
    }

    /**
     * Marquer la contribution comme annulée
     */
    public function markAsCancelled(): void
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }

    /**
     * Obtenir le montant formaté
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' CAD';
    }

    /**
     * Obtenir le statut traduit
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'En attente',
            'paid' => 'Payée',
            'cancelled' => 'Annulée',
            default => ucfirst($this->status),
        };
    }

    /**
     * Obtenir le nombre de jours de retard
     */
    public function getOverdueDaysAttribute(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return now()->diffInDays($this->due_date);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Sponsorship extends Model
{
    use HasFactory;

    protected $fillable = [
        'sponsor_id',
        'sponsorship_code',
        'prospect_first_name',
        'prospect_last_name',
        'prospect_email',
        'prospect_phone',
        'status',
        'confirmed_at',
        'expires_at',
        'notes',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Relation avec le parrain
     */
    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'sponsor_id');
    }

    /**
     * Scope pour les parrainages en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour les parrainages confirmés
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope pour les parrainages complétés
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope pour les parrainages expirés
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    /**
     * Vérifier si le parrainage est en attente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Vérifier si le parrainage est confirmé
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Vérifier si le parrainage est complété
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Vérifier si le parrainage est expiré
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || 
               ($this->expires_at && $this->expires_at->isPast());
    }

    /**
     * Générer un code de parrainage unique
     */
    public static function generateSponsorshipCode(): string
    {
        do {
            $code = 'SP' . strtoupper(Str::random(8));
        } while (self::where('sponsorship_code', $code)->exists());

        return $code;
    }

    /**
     * Confirmer le parrainage
     */
    public function confirm(): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Marquer comme complété
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
        ]);
    }

    /**
     * Marquer comme expiré
     */
    public function markAsExpired(): void
    {
        $this->update([
            'status' => 'expired',
        ]);
    }

    /**
     * Obtenir le nom complet du prospect
     */
    public function getProspectFullNameAttribute(): string
    {
        return $this->prospect_first_name . ' ' . $this->prospect_last_name;
    }

    /**
     * Obtenir le statut traduit
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'En attente',
            'confirmed' => 'Confirmé',
            'completed' => 'Complété',
            'expired' => 'Expiré',
            default => ucfirst($this->status),
        };
    }

    /**
     * Boot method pour les événements
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sponsorship) {
            if (!$sponsorship->sponsorship_code) {
                $sponsorship->sponsorship_code = self::generateSponsorshipCode();
            }
            
            if (!$sponsorship->expires_at) {
                $sponsorship->expires_at = now()->addDays(30);
            }
        });
    }
}

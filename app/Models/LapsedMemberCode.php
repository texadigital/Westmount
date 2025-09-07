<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LapsedMemberCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'code',
        'expires_at',
        'is_used',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    /**
     * Relation avec le membre
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Scope pour les codes non utilisés
     */
    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }

    /**
     * Scope pour les codes valides (non expirés)
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope pour les codes disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->unused()->valid();
    }

    /**
     * Vérifier si le code est valide
     */
    public function isValid(): bool
    {
        return !$this->is_used && $this->expires_at->isFuture();
    }

    /**
     * Marquer comme utilisé
     */
    public function markAsUsed(): void
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
        ]);
    }

    /**
     * Générer un code de réactivation unique
     */
    public static function generateCode(): string
    {
        do {
            $code = 'LAP' . strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Créer un code de réactivation pour un membre
     */
    public static function createForMember(Member $member, int $daysValid = 30): self
    {
        return self::create([
            'member_id' => $member->id,
            'code' => self::generateCode(),
            'expires_at' => now()->addDays($daysValid),
        ]);
    }

    /**
     * Trouver un code valide
     */
    public static function findValidCode(string $code): ?self
    {
        return self::where('code', $code)
                   ->available()
                   ->first();
    }
}

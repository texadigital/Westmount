<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class Member extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'member_number',
        'pin_code',
        'first_name',
        'last_name',
        'birth_date',
        'phone',
        'email',
        'address',
        'city',
        'province',
        'postal_code',
        'country',
        'canadian_status_proof',
        'member_type_id',
        'organization_id',
        'sponsor_id',
        'lapsed_code',
        'reactivated_at',
        'reactivation_code',
        'is_active',
        'email_verified_at',
        'phone_verified_at',
    ];

    protected $hidden = [
        'pin_code',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'reactivated_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec le type de membre
     */
    public function memberType(): BelongsTo
    {
        return $this->belongsTo(MemberType::class);
    }

    /**
     * Relation avec l'organisation
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Relation avec le parrain
     */
    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'sponsor_id');
    }

    /**
     * Relation avec les filleuls
     */
    public function sponsoredMembers(): HasMany
    {
        return $this->hasMany(Member::class, 'sponsor_id');
    }

    /**
     * Relation avec les adhésions
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Relation avec l'adhésion active
     */
    public function activeMembership(): HasOne
    {
        return $this->hasOne(Membership::class)->where('is_active', true);
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
     * Relation avec les contributions décès
     */
    public function deathContributions(): HasMany
    {
        return $this->hasMany(DeathContribution::class);
    }

    /**
     * Relation avec les parrainages
     */
    public function sponsorships(): HasMany
    {
        return $this->hasMany(Sponsorship::class, 'sponsor_id');
    }

    /**
     * Générer un numéro de membre unique
     */
    public static function generateMemberNumber(): string
    {
        do {
            $number = 'WM' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('member_number', $number)->exists());

        return $number;
    }

    /**
     * Hasher le code PIN
     */
    public function setPinCodeAttribute($value)
    {
        $this->attributes['pin_code'] = Hash::make($value);
    }

    /**
     * Vérifier le code PIN
     */
    public function verifyPinCode($pin): bool
    {
        return Hash::check($pin, $this->pin_code);
    }

    /**
     * Calculer l'âge du membre
     */
    public function getAgeAttribute(): int
    {
        return $this->birth_date->age;
    }

    /**
     * Nom complet du membre
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Scope pour les membres actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les membres avec adhésion en retard
     */
    public function scopeOverdue($query)
    {
        return $query->whereHas('memberships', function ($q) {
            $q->where('status', 'overdue');
        });
    }

    /**
     * Scope pour les membres caducs
     */
    public function scopeLapsed($query)
    {
        return $query->whereHas('memberships', function ($q) {
            $q->where('status', 'lapsed');
        });
    }

    /**
     * Relation avec les codes de réactivation
     */
    public function lapsedCodes(): HasMany
    {
        return $this->hasMany(LapsedMemberCode::class);
    }

    /**
     * Relation avec les documents du membre
     */
    public function documents(): HasMany
    {
        return $this->hasMany(MemberDocument::class);
    }

    /**
     * Vérifier si le membre est caduc
     */
    public function isLapsed(): bool
    {
        return $this->activeMembership && $this->activeMembership->status === 'lapsed';
    }

    /**
     * Statut de conformité: En règle / En retard / Caduque
     */
    public function getComplianceStatusAttribute(): string
    {
        // Caduque si l'adhésion est caduque
        if ($this->isLapsed()) {
            return 'Caduque';
        }

        // En retard si au moins une contribution décès est overdue
        $hasOverdueDeath = $this->deathContributions()
            ->where('status', 'overdue')
            ->exists();

        if ($hasOverdueDeath || ($this->activeMembership && $this->activeMembership->isOverdue())) {
            return 'En retard';
        }

        return 'En règle';
    }

    /**
     * Générer un code de réactivation
     */
    public function generateReactivationCode(): string
    {
        $code = LapsedMemberCode::createForMember($this);
        $this->update(['reactivation_code' => $code->code]);
        return $code->code;
    }

    /**
     * Réactiver le membre
     */
    public function reactivate(): void
    {
        $this->update([
            'is_active' => true,
            'reactivated_at' => now(),
            'lapsed_code' => null,
            'reactivation_code' => null,
        ]);
    }
}

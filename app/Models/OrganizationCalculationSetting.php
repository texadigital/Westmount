<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationCalculationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'adhesion_fee_per_member',
        'adhesion_fee_formula',
        'contribution_fee_formula',
        'include_penalties',
        'discount_percentage',
        'min_members_for_discount',
        'description',
        'is_active',
    ];

    protected $casts = [
        'adhesion_fee_per_member' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'include_penalties' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the active organization calculation setting
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Calculate adhesion fees for an organization
     */
    public function calculateAdhesionFees($memberCount)
    {
        $baseFee = $this->adhesion_fee_per_member * $memberCount;
        
        // Apply discount if applicable
        if ($this->min_members_for_discount && $memberCount >= $this->min_members_for_discount) {
            $discount = $baseFee * ($this->discount_percentage / 100);
            $baseFee -= $discount;
        }
        
        return max(0, $baseFee);
    }

    /**
     * Calculate contribution fees for an organization
     */
    public function calculateContributionFees($individualContributions)
    {
        if ($this->contribution_fee_formula === 'sum_of_individual_contributions') {
            return array_sum($individualContributions);
        }
        
        // Add more formula options as needed
        return array_sum($individualContributions);
    }
}

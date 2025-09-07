<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrganizationCalculationSetting;

class OrganizationCalculationSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrganizationCalculationSetting::create([
            'name' => 'Default Organization Calculation',
            'adhesion_fee_per_member' => 50.00,
            'adhesion_fee_formula' => 'adhesion_fee_per_member * member_count',
            'contribution_fee_formula' => 'sum_of_individual_contributions',
            'include_penalties' => false,
            'discount_percentage' => 0.00,
            'min_members_for_discount' => null,
            'description' => 'Standard calculation: $50 per member for adhesion fees, sum of individual contributions for death contributions.',
            'is_active' => true,
        ]);
    }
}

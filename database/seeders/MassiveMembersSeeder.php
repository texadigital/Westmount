<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MemberType;
use App\Models\Fund;

class MassiveMembersSeeder extends Seeder
{
    /**
     * Seed 1,640 active members and set funds total to 82,000.
     */
    public function run(): void
    {
        // Ensure member types exist
        $types = MemberType::query()->pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [strtolower($name) => $id];
        });
        $fallbackTypeId = $types['régulier'] ?? $types['regulier'] ?? $types->first();

        $target = 1640;
        $created = 0;

        // Keep creating members until total count reaches target
        $index = 1;
        while (Member::count() < $target) {
            // Find next available email like member{N}@example.com
            while (Member::where('email', "member{$index}@example.com")->exists()) {
                $index++;
            }

            $email = "member{$index}@example.com";

            $member = Member::firstOrCreate(
                ['email' => $email],
                [
                'member_number' => Member::generateMemberNumber(),
                'pin_code' => (string) random_int(1000, 9999),
                'first_name' => 'Membre',
                'last_name' => (string) $index,
                'birth_date' => now()->subYears(rand(18, 75))->format('Y-m-d'),
                'phone' => sprintf('(514) 555-%04d', $index % 10000),
                'address' => "#{$index} Rue Exemple, Montréal",
                'city' => 'Montréal',
                'province' => 'Québec',
                'postal_code' => 'H' . rand(1,9) . 'A ' . rand(1,9) . 'A' . rand(1,9),
                'country' => 'Canada',
                'canadian_status_proof' => 'Carte de citoyenneté',
                'member_type_id' => $fallbackTypeId,
                'is_active' => true,
                'email_verified_at' => now(),
                ]
            );

            Membership::firstOrCreate([
                'member_id' => $member->id,
                'is_active' => true,
            ], [
                'member_id' => $member->id,
                'status' => 'active',
                'start_date' => now()->subMonths(rand(0, 11)),
                'end_date' => now()->addYear(),
                'adhesion_fee_paid' => 50.00,
                'total_contributions_paid' => 0.00,
                'is_active' => true,
            ]);

            $created++;
            $index++;
        }

        // Set funds so that HomeController shows Fonds disponibles = 82,000
        // Home uses sum of all active funds' current_balance
        // We'll set the general fund to 82,000 and others to 0, preserving is_active
        $general = Fund::firstOrCreate(['type' => 'general'], [
            'name' => 'Fonds Général',
            'description' => "Fonds principal",
            'current_balance' => 0,
            'total_contributions' => 0,
            'total_distributions' => 0,
            'is_active' => true,
        ]);
        $general->update(['current_balance' => 82000]);

        // Optionally zero out other active funds' current_balance
        Fund::where('is_active', true)->where('id', '!=', $general->id)->update(['current_balance' => 0]);

        $this->command?->info("MassiveMembersSeeder: added {$created} members (target 1640). Funds set to 82,000 CAD.");
    }
}

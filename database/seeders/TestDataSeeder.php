<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\Fund;
use App\Models\Sponsorship;
use App\Models\Contribution;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer une organisation de test simple
        $organization = Organization::firstOrCreate(
            ['business_number' => '123456789RT0001'],
            [
                'name' => 'Association Test Montréal',
                'contact_person' => 'Jean Dupont',
                'contact_email' => 'contact@testmontreal.ca',
                'contact_phone' => '(514) 555-0123',
                'address' => '123 Rue Test, Montréal',
                'city' => 'Montréal',
                'province' => 'Québec',
                'postal_code' => 'H1A 1A1',
                'country' => 'Canada',
                'is_active' => true,
            ]
        );

        // Créer un seul membre de test
        $member = Member::firstOrCreate(
            ['email' => 'marie.tremblay@email.com'],
            [
                'member_number' => Member::generateMemberNumber(),
                'pin_code' => '1234',
                'first_name' => 'Marie',
                'last_name' => 'Tremblay',
                'birth_date' => '1985-03-15',
                'phone' => '(514) 555-0101',
                'address' => '456 Avenue des Fleurs, Montréal',
                'city' => 'Montréal',
                'province' => 'Québec',
                'postal_code' => 'H2B 2B2',
                'canadian_status_proof' => 'Carte de citoyenneté',
                'member_type_id' => 1, // Régulier
                'organization_id' => $organization->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Créer une adhésion pour le membre
        $membership = Membership::firstOrCreate(
            ['member_id' => $member->id, 'is_active' => true],
            [
                'status' => 'active',
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(10),
                'adhesion_fee_paid' => 50.00,
                'total_contributions_paid' => 10.00,
                'is_active' => true,
            ]
        );

        // Créer un paiement pour l'adhésion
        Payment::firstOrCreate(
            [
                'membership_id' => $membership->id,
                'member_id' => $member->id,
                'type' => 'adhesion',
            ],
            [
                'amount' => 50.00,
                'currency' => 'CAD',
                'status' => 'completed',
                'payment_method' => 'stripe',
                'description' => 'Frais d\'adhésion initiale',
                'paid_at' => now()->subDays(5),
            ]
        );

        // Mettre à jour les statistiques des fonds
        $generalFund = Fund::where('type', 'general')->first();
        if ($generalFund) {
            $generalFund->update([
                'current_balance' => 50.00,
                'total_contributions' => 50.00,
                'total_distributions' => 0.00,
            ]);
        }

        $deathFund = Fund::where('type', 'death_benefit')->first();
        if ($deathFund) {
            $deathFund->update([
                'current_balance' => 10.00,
                'total_contributions' => 10.00,
                'total_distributions' => 0.00,
            ]);
        }

        // Mettre à jour les statistiques de l'organisation
        $organization->updateStatistics();

        // Créer des données de test pour les parrainages
        $sponsor = Member::firstOrCreate(
            ['email' => 'pierre.martin@email.com'],
            [
                'member_number' => Member::generateMemberNumber(),
                'pin_code' => '5678',
                'first_name' => 'Pierre',
                'last_name' => 'Martin',
                'birth_date' => '1975-08-20',
                'phone' => '(514) 555-0202',
                'address' => '789 Boulevard Saint-Laurent, Montréal',
                'city' => 'Montréal',
                'province' => 'Québec',
                'postal_code' => 'H3C 3C3',
                'canadian_status_proof' => 'Carte de résident permanent',
                'member_type_id' => 1, // Régulier
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Créer un parrainage
        Sponsorship::firstOrCreate(
            ['sponsorship_code' => 'SPONSOR001'],
            [
                'sponsor_id' => $sponsor->id,
                'prospect_first_name' => 'Sophie',
                'prospect_last_name' => 'Lavoie',
                'prospect_email' => 'sophie.lavoie@email.com',
                'prospect_phone' => '(514) 555-0303',
                'status' => 'pending',
                'expires_at' => now()->addDays(30),
                'notes' => 'Prospect intéressé par l\'association',
            ]
        );

        // Créer une contribution de décès
        Contribution::firstOrCreate(
            [
                'member_id' => $member->id,
                'deceased_member_id' => $sponsor->id,
            ],
            [
                'amount' => 10.00,
                'status' => 'pending',
                'due_date' => now()->addDays(15),
                'notes' => 'Contribution pour le décès de Pierre Martin',
            ]
        );

        // Créer quelques adhésions en retard pour tester les widgets
        $overdueMember = Member::firstOrCreate(
            ['email' => 'jean.dupont@email.com'],
            [
                'member_number' => Member::generateMemberNumber(),
                'pin_code' => '9999',
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'birth_date' => '1980-12-10',
                'phone' => '(514) 555-0404',
                'address' => '321 Rue Sherbrooke, Montréal',
                'city' => 'Montréal',
                'province' => 'Québec',
                'postal_code' => 'H4D 4D4',
                'canadian_status_proof' => 'Carte de citoyenneté',
                'member_type_id' => 1, // Régulier
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $overdueMembership = Membership::firstOrCreate(
            ['member_id' => $overdueMember->id, 'is_active' => true],
            [
                'status' => 'overdue',
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addMonths(6),
                'adhesion_fee_paid' => 50.00,
                'total_contributions_paid' => 0.00,
                'amount_due' => 20.00,
                'next_payment_due' => now()->subDays(15),
                'overdue_days' => 15,
                'is_active' => true,
            ]
        );
    }
}

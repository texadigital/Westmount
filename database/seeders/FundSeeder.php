<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fund;

class FundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $funds = [
            [
                'name' => 'Fonds Général',
                'description' => 'Fonds principal de l\'association pour les opérations générales',
                'current_balance' => 0.00,
                'total_contributions' => 0.00,
                'total_distributions' => 0.00,
                'type' => 'general',
                'is_active' => true,
            ],
            [
                'name' => 'Fonds de Prestation de Décès',
                'description' => 'Fonds dédié aux prestations de décès pour les familles des membres',
                'current_balance' => 0.00,
                'total_contributions' => 0.00,
                'total_distributions' => 0.00,
                'type' => 'death_benefit',
                'is_active' => true,
            ],
            [
                'name' => 'Fonds d\'Urgence',
                'description' => 'Fonds d\'urgence pour les situations critiques',
                'current_balance' => 0.00,
                'total_contributions' => 0.00,
                'total_distributions' => 0.00,
                'type' => 'emergency',
                'is_active' => true,
            ],
        ];

        foreach ($funds as $fund) {
            Fund::updateOrCreate(
                ['type' => $fund['type']], // Find by type
                $fund // Update or create with all data
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MemberType;

class MemberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $memberTypes = [
            [
                'name' => 'régulier',
                'description' => 'Membre régulier adulte (moins de 68 ans)',
                'adhesion_fee' => 50.00,
                'death_contribution' => 10.00,
                'min_age' => 18,
                'max_age' => 67,
                'is_active' => true,
            ],
            [
                'name' => 'senior',
                'description' => 'Membre senior (68 ans et plus à l\'adhésion)',
                'adhesion_fee' => 50.00,
                'death_contribution' => 2.00,
                'min_age' => 68,
                'max_age' => null,
                'is_active' => true,
            ],
            [
                'name' => 'junior',
                'description' => 'Membre junior (mineurs)',
                'adhesion_fee' => 50.00,
                'death_contribution' => 2.00,
                'min_age' => null,
                'max_age' => 17,
                'is_active' => true,
            ],
            [
                'name' => 'association',
                'description' => 'Association ou organisme partenaire',
                'adhesion_fee' => 50.00,
                'death_contribution' => 10.00,
                'min_age' => null,
                'max_age' => null,
                'is_active' => true,
            ],
        ];

        foreach ($memberTypes as $memberType) {
            MemberType::create($memberType);
        }
    }
}

                'description' => 'Membre régulier adulte (moins de 68 ans)',
                'adhesion_fee' => 50.00,
                'death_contribution' => 10.00,
                'min_age' => 18,
                'max_age' => 67,
                'is_active' => true,
            ],
            [
                'name' => 'senior',
                'description' => 'Membre senior (68 ans et plus à l\'adhésion)',
                'adhesion_fee' => 50.00,
                'death_contribution' => 2.00,
                'min_age' => 68,
                'max_age' => null,
                'is_active' => true,
            ],
            [
                'name' => 'junior',
                'description' => 'Membre junior (mineurs)',
                'adhesion_fee' => 50.00,
                'death_contribution' => 2.00,
                'min_age' => null,
                'max_age' => 17,
                'is_active' => true,
            ],
            [
                'name' => 'association',
                'description' => 'Association ou organisme partenaire',
                'adhesion_fee' => 50.00,
                'death_contribution' => 10.00,
                'min_age' => null,
                'max_age' => null,
                'is_active' => true,
            ],
        ];

        foreach ($memberTypes as $memberType) {
            MemberType::create($memberType);
        }
    }
}

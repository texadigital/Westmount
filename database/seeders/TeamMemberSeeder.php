<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeamMember;

class TeamMemberSeeder extends Seeder
{
    /**
     * Seed the application's database with initial team members.
     */
    public function run(): void
    {
        // Clear existing to avoid duplicates when reseeding
        TeamMember::truncate();

        $members = [
            [
                'name' => 'Marie Dubois',
                'role' => 'Présidente',
                'bio' => "Membre depuis 2010, Marie dirige l'association avec passion et dévouement.",
                'photo_path' => null,
                'order_column' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Jean Tremblay',
                'role' => 'Trésorier',
                'bio' => "Expert-comptable, Jean assure la transparence financière de l'association.",
                'photo_path' => null,
                'order_column' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Sophie Martin',
                'role' => 'Secrétaire',
                'bio' => "Sophie coordonne les communications et l'administration quotidienne.",
                'photo_path' => null,
                'order_column' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($members as $m) {
            TeamMember::create($m);
        }
    }
}

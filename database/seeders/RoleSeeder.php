<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrateur',
                'description' => 'Accès complet à tous les aspects du système',
                'permissions' => array_keys(Role::getAvailablePermissions()),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrateur',
                'description' => 'Gestion complète des membres et organisations',
                'permissions' => [
                    'members.view', 'members.create', 'members.edit', 'members.delete',
                    'organizations.view', 'organizations.create', 'organizations.edit', 'organizations.delete',
                    'payments.view', 'payments.create', 'payments.edit', 'payments.confirm',
                    'settings.view', 'settings.edit',
                    'reports.view',
                ],
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'manager',
                'display_name' => 'Gestionnaire',
                'description' => 'Gestion des membres et paiements',
                'permissions' => [
                    'members.view', 'members.create', 'members.edit',
                    'organizations.view', 'organizations.create', 'organizations.edit',
                    'payments.view', 'payments.create', 'payments.edit', 'payments.confirm',
                    'reports.view',
                ],
                'is_active' => true,
                'is_system' => false,
            ],
            [
                'name' => 'viewer',
                'display_name' => 'Observateur',
                'description' => 'Consultation uniquement',
                'permissions' => [
                    'members.view',
                    'organizations.view',
                    'payments.view',
                    'reports.view',
                ],
                'is_active' => true,
                'is_system' => false,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}

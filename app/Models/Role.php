<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions',
        'is_active',
        'is_system',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    /**
     * Get users with this role
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if role has permission
     */
    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Get all available permissions
     */
    public static function getAvailablePermissions()
    {
        return [
            'members.view' => 'Voir les membres',
            'members.create' => 'Créer des membres',
            'members.edit' => 'Modifier les membres',
            'members.delete' => 'Supprimer des membres',
            'organizations.view' => 'Voir les organisations',
            'organizations.create' => 'Créer des organisations',
            'organizations.edit' => 'Modifier les organisations',
            'organizations.delete' => 'Supprimer des organisations',
            'payments.view' => 'Voir les paiements',
            'payments.create' => 'Créer des paiements',
            'payments.edit' => 'Modifier les paiements',
            'payments.confirm' => 'Confirmer les paiements',
            'settings.view' => 'Voir les paramètres',
            'settings.edit' => 'Modifier les paramètres',
            'reports.view' => 'Voir les rapports',
            'users.view' => 'Voir les utilisateurs',
            'users.create' => 'Créer des utilisateurs',
            'users.edit' => 'Modifier les utilisateurs',
            'users.delete' => 'Supprimer des utilisateurs',
            'roles.view' => 'Voir les rôles',
            'roles.create' => 'Créer des rôles',
            'roles.edit' => 'Modifier les rôles',
            'roles.delete' => 'Supprimer les rôles',
        ];
    }
}

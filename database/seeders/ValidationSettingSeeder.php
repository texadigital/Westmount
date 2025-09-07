<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ValidationSetting;

class ValidationSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $validationSettings = [
            [
                'field_name' => 'first_name',
                'field_type' => 'text',
                'rules' => ['string', 'max:255'],
                'custom_messages' => [
                    'first_name.required' => 'Le prénom est obligatoire.',
                    'first_name.string' => 'Le prénom doit être une chaîne de caractères.',
                    'first_name.max' => 'Le prénom ne peut pas dépasser 255 caractères.',
                ],
                'is_required' => true,
                'min_length' => 2,
                'max_length' => 255,
                'help_text' => 'Entrez votre prénom (2-255 caractères)',
                'is_active' => true,
            ],
            [
                'field_name' => 'last_name',
                'field_type' => 'text',
                'rules' => ['string', 'max:255'],
                'custom_messages' => [
                    'last_name.required' => 'Le nom est obligatoire.',
                    'last_name.string' => 'Le nom doit être une chaîne de caractères.',
                    'last_name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
                ],
                'is_required' => true,
                'min_length' => 2,
                'max_length' => 255,
                'help_text' => 'Entrez votre nom de famille (2-255 caractères)',
                'is_active' => true,
            ],
            [
                'field_name' => 'email',
                'field_type' => 'email',
                'rules' => ['email', 'max:255', 'unique:members,email'],
                'custom_messages' => [
                    'email.required' => 'L\'adresse email est obligatoire.',
                    'email.email' => 'L\'adresse email doit être valide.',
                    'email.unique' => 'Cette adresse email est déjà utilisée.',
                ],
                'is_required' => true,
                'max_length' => 255,
                'help_text' => 'Entrez une adresse email valide',
                'is_active' => true,
            ],
            [
                'field_name' => 'phone',
                'field_type' => 'phone',
                'rules' => ['string', 'max:20'],
                'custom_messages' => [
                    'phone.required' => 'Le numéro de téléphone est obligatoire.',
                    'phone.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
                ],
                'is_required' => true,
                'min_length' => 10,
                'max_length' => 20,
                'pattern' => '/^[\+]?[0-9\s\-\(\)]+$/',
                'help_text' => 'Entrez votre numéro de téléphone (10-20 caractères)',
                'is_active' => true,
            ],
            [
                'field_name' => 'pin_code',
                'field_type' => 'text',
                'rules' => ['string', 'min:4', 'max:6', 'unique:members,pin_code'],
                'custom_messages' => [
                    'pin_code.required' => 'Le code PIN est obligatoire.',
                    'pin_code.string' => 'Le code PIN doit être une chaîne de caractères.',
                    'pin_code.min' => 'Le code PIN doit contenir au moins 4 caractères.',
                    'pin_code.max' => 'Le code PIN ne peut pas dépasser 6 caractères.',
                    'pin_code.unique' => 'Ce code PIN est déjà utilisé.',
                ],
                'is_required' => true,
                'min_length' => 4,
                'max_length' => 6,
                'pattern' => '/^[0-9]+$/',
                'help_text' => 'Choisissez un code PIN de 4 à 6 chiffres',
                'is_active' => true,
            ],
        ];

        foreach ($validationSettings as $setting) {
            ValidationSetting::create($setting);
        }
    }
}

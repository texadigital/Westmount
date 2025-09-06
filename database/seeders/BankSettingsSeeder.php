<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class BankSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bank payment settings
        Setting::set(
            'bank_name',
            'Association Westmount',
            'text',
            'bank',
            'Nom de la banque pour les virements'
        );

        Setting::set(
            'bank_account',
            '1234567890',
            'text',
            'bank',
            'Numéro de compte bancaire'
        );

        Setting::set(
            'bank_transit',
            '00123',
            'text',
            'bank',
            'Numéro de transit bancaire'
        );

        Setting::set(
            'bank_swift',
            '',
            'text',
            'bank',
            'Code SWIFT/BIC de la banque'
        );

        Setting::set(
            'bank_address',
            '',
            'textarea',
            'bank',
            'Adresse de la banque'
        );

        Setting::set(
            'bank_instructions',
            'Veuillez inclure votre numéro de membre dans la référence du virement.',
            'textarea',
            'bank',
            'Instructions pour les virements bancaires'
        );

        // Payment settings
        Setting::set(
            'payment_currency',
            'CAD',
            'text',
            'payment',
            'Devise des paiements'
        );

        Setting::set(
            'payment_method',
            'bank_transfer',
            'text',
            'payment',
            'Méthode de paiement par défaut'
        );

        Setting::set(
            'payment_instructions',
            'Effectuez votre virement bancaire selon les informations ci-dessous. Votre paiement sera confirmé une fois le virement reçu.',
            'textarea',
            'payment',
            'Instructions générales de paiement'
        );
    }
}
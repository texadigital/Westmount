<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class FooterSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Requested contact details
        $address = "573 Pierre-Dugua-De Mons, L'Assomption, QC J5W 0E3\nCANADA";
        $phone = '514-566-4029';
        $email = 'info@associationwestmount.com';
        $aboutText = "Une communauté d'entraide et de solidarité qui accompagne ses membres dans les moments difficiles.";
        $hours = 'Lun-Ven: 9h-17h';

        Setting::set('footer_about_text', $aboutText, 'text', 'footer');
        Setting::set('footer_phone', $phone, 'text', 'footer');
        Setting::set('footer_email', $email, 'text', 'footer');
        Setting::set('footer_address', $address, 'text', 'footer');
        Setting::set('footer_hours', $hours, 'text', 'footer');

        // Socials empty by default; admin can fill later
        Setting::set('footer_facebook', '', 'text', 'footer');
        Setting::set('footer_twitter', '', 'text', 'footer');
        Setting::set('footer_linkedin', '', 'text', 'footer');
        Setting::set('footer_instagram', '', 'text', 'footer');
    }
}

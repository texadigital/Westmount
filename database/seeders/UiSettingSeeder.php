<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UiSetting;

class UiSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UiSetting::create([
            'name' => 'Default UI Settings',
            'primary_color' => '#3B82F6',
            'secondary_color' => '#6B7280',
            'accent_color' => '#10B981',
            'background_color' => '#FFFFFF',
            'text_color' => '#1F2937',
            'logo_url' => null,
            'favicon_url' => null,
            'site_title' => 'Association Westmount',
            'site_description' => 'Association communautaire de Westmount',
            'contact_email' => 'info@associationwestmount.com',
            'contact_phone' => '+1 (514) 555-0123',
            'footer_text' => '© 2024 Association Westmount. Tous droits réservés.',
            'custom_css' => null,
            'is_active' => true,
        ]);
    }
}

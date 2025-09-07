<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailSetting;

class EmailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailSetting::create([
            'name' => 'Default Email Settings',
            'mailer' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'username' => 'noreply@associationwestmount.com',
            'password' => 'your-app-password',
            'encryption' => 'tls',
            'from_address' => 'noreply@associationwestmount.com',
            'from_name' => 'Association Westmount',
            'is_active' => true,
        ]);
    }
}

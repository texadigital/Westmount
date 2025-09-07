<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InteracSetting;

class InteracSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InteracSetting::create([
            'name' => 'Default Interac Settings',
            'email' => 'payments@associationwestmount.com',
            'security_question' => 'What is the name of your first pet?',
            'security_answer' => 'Association Westmount',
            'instructions' => 'Please send the Interac e-Transfer to the email above with the security question and answer provided. Include your member number and payment reference in the message.',
            'is_active' => true,
        ]);
    }
}

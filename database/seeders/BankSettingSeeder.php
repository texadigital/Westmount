<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankSetting;

class BankSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BankSetting::create([
            'name' => 'Default Bank Settings',
            'bank_name' => 'Banque Nationale du Canada',
            'account_holder' => 'Association Westmount',
            'account_number' => '1234567890',
            'transit_number' => '12345',
            'institution_number' => '006',
            'swift_code' => 'BNCACAMM',
            'routing_number' => '00060012345',
            'instructions' => 'Please include your member number and payment reference in the transfer description. Processing time: 1-3 business days.',
            'is_active' => true,
        ]);
    }
}

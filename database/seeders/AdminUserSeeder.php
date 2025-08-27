<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrateur Westmount',
            'email' => 'admin@westmount.ca',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
    }
}

            'email' => 'admin@westmount.ca',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
    }
}

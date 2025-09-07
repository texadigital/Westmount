<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LapsedMemberCode;
use App\Models\Member;

class MemberRegistrationCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some test registration codes for existing members
        $members = Member::take(3)->get();
        
        foreach ($members as $member) {
            // Create a registration code for each member
            LapsedMemberCode::create([
                'member_id' => $member->id,
                'code' => LapsedMemberCode::generateCode(),
                'expires_at' => now()->addDays(30),
                'is_used' => false,
            ]);
        }

        // Create some standalone registration codes (not tied to specific members)
        for ($i = 0; $i < 5; $i++) {
            LapsedMemberCode::create([
                'member_id' => null, // Standalone codes
                'code' => LapsedMemberCode::generateCode(),
                'expires_at' => now()->addDays(30),
                'is_used' => false,
            ]);
        }

        echo "✅ Created " . (3 + 5) . " member registration codes\n";
        echo "📝 Registration codes:\n";
        
        $codes = LapsedMemberCode::where('is_used', false)->get();
        foreach ($codes as $code) {
            echo "   - {$code->code} (expires: {$code->expires_at->format('Y-m-d H:i:s')})\n";
        }
    }
}

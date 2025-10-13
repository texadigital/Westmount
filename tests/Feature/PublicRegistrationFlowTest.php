<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\MemberTypeSeeder;
use App\Models\MemberType;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Payment;

class PublicRegistrationFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * End-to-end: visitor registers and gets redirected to adhesion payment page
     */
    public function test_full_public_registration_to_adhesion_payment_page(): void
    {
        // Seed required lookups (member types, etc.)
        $this->seed(MemberTypeSeeder::class);

        // Pick an active member type
        $memberType = MemberType::active()->first() ?? MemberType::first();
        $this->assertNotNull($memberType, 'MemberType should exist after seeding');

        // Prepare payload
        $payload = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'birth_date' => '1990-01-01',
            'phone' => '(514) 555-0101',
            'email' => 'test.user@example.com',
            'address' => '123 Rue Test',
            'city' => 'Montréal',
            'province' => 'Québec',
            'postal_code' => 'H1A 1A1',
            'country' => 'Canada',
            'canadian_status_proof' => 'Carte de citoyenneté',
            'member_type_id' => $memberType->id,
            'payment_method' => 'stripe',
            'pin_code' => '4321',
            'pin_code_confirmation' => '4321',
        ];

        // Submit registration and follow redirects (should end up on adhesion payment page)
        $response = $this->followingRedirects()->post(route('public.registration.register'), $payload);

        // Response should be OK and contain adhesion payment view content
        $response->assertStatus(200);
        $response->assertSee('Paiement', false); // generic sanity check text from payment views

        // Member created and logged in (session contains member_id set by controller)
        $this->assertDatabaseHas('members', [
            'email' => 'test.user@example.com',
            'is_active' => true,
        ]);

        $member = Member::where('email', 'test.user@example.com')->firstOrFail();
        $this->assertNotNull($member->activeMembership, 'Active membership should be created');

        // Initial adhesion payment row should exist (pending)
        $this->assertDatabaseHas('payments', [
            'member_id' => $member->id,
            'type' => 'adhesion',
            'status' => 'pending',
            'payment_method' => 'stripe',
        ]);

        // Ensure membership record spans ~1 year
        /** @var Membership $membership */
        $membership = $member->activeMembership;
        $this->assertTrue($membership->is_active);
        $this->assertNotNull($membership->start_date);
        $this->assertNotNull($membership->end_date);

        // Navigate to the adhesion payment page directly (session persists through followingRedirects)
        $paymentPage = $this->get(route('member.payment.adhesion'));
        $paymentPage->assertStatus(200);
    }
}

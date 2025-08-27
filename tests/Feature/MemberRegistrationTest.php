<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\Sponsorship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class MemberRegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer les types de membres
        MemberType::factory()->create([
            'name' => 'Régulier',
            'adhesion_fee' => 50.00,
            'death_contribution' => 10.00,
            'min_age' => 18,
            'max_age' => 64,
        ]);
    }

    /** @test */
    public function user_can_access_registration_form()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('public.registration');
        $response->assertViewHas('memberTypes');
    }

    /** @test */
    public function user_can_register_with_valid_data()
    {
        $memberType = MemberType::first();

        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'birth_date' => '1990-01-01',
            'phone' => '514-555-0123',
            'email' => 'john.doe@example.com',
            'address' => '123 Main St',
            'city' => 'Montreal',
            'province' => 'Québec',
            'postal_code' => 'H1A 1A1',
            'country' => 'Canada',
            'canadian_status_proof' => 'Carte de citoyenneté',
            'member_type_id' => $memberType->id,
            'pin_code' => '1234',
            'pin_code_confirmation' => '1234',
        ];

        $response = $this->post('/register', $data);

        $response->assertRedirect('/register/success');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('members', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);

        $this->assertDatabaseHas('memberships', [
            'status' => 'active',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('payments', [
            'type' => 'adhesion',
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function user_cannot_register_with_invalid_age()
    {
        $memberType = MemberType::first();

        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'birth_date' => '2010-01-01', // Trop jeune
            'phone' => '514-555-0123',
            'email' => 'john.doe@example.com',
            'address' => '123 Main St',
            'city' => 'Montreal',
            'province' => 'Québec',
            'postal_code' => 'H1A 1A1',
            'country' => 'Canada',
            'canadian_status_proof' => 'Carte de citoyenneté',
            'member_type_id' => $memberType->id,
            'pin_code' => '1234',
            'pin_code_confirmation' => '1234',
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionHasErrors(['birth_date']);
    }

    /** @test */
    public function user_can_register_with_valid_sponsorship_code()
    {
        $memberType = MemberType::first();
        $sponsor = Member::factory()->create();
        
        $sponsorship = Sponsorship::factory()->create([
            'sponsor_id' => $sponsor->id,
            'status' => 'pending',
            'expires_at' => now()->addDays(30),
        ]);

        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'birth_date' => '1985-01-01',
            'phone' => '514-555-0124',
            'email' => 'jane.smith@example.com',
            'address' => '456 Oak St',
            'city' => 'Montreal',
            'province' => 'Québec',
            'postal_code' => 'H2B 2B2',
            'country' => 'Canada',
            'canadian_status_proof' => 'Permis de résidence permanente',
            'member_type_id' => $memberType->id,
            'sponsorship_code' => $sponsorship->sponsorship_code,
            'pin_code' => '5678',
            'pin_code_confirmation' => '5678',
        ];

        $response = $this->post('/register', $data);

        $response->assertRedirect('/register/success');

        $this->assertDatabaseHas('members', [
            'email' => 'jane.smith@example.com',
            'sponsor_id' => $sponsor->id,
        ]);

        $this->assertDatabaseHas('sponsorships', [
            'id' => $sponsorship->id,
            'status' => 'confirmed',
        ]);
    }

    /** @test */
    public function user_cannot_register_with_invalid_sponsorship_code()
    {
        $memberType = MemberType::first();

        $data = [
            'first_name' => 'Bob',
            'last_name' => 'Wilson',
            'birth_date' => '1975-01-01',
            'phone' => '514-555-0125',
            'email' => 'bob.wilson@example.com',
            'address' => '789 Pine St',
            'city' => 'Montreal',
            'province' => 'Québec',
            'postal_code' => 'H3C 3C3',
            'country' => 'Canada',
            'canadian_status_proof' => 'Carte de citoyenneté',
            'member_type_id' => $memberType->id,
            'sponsorship_code' => 'INVALID123',
            'pin_code' => '9012',
            'pin_code_confirmation' => '9012',
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionHasErrors(['sponsorship_code']);
    }

    /** @test */
    public function sponsorship_code_validation_works_via_ajax()
    {
        $sponsor = Member::factory()->create();
        $sponsorship = Sponsorship::factory()->create([
            'sponsor_id' => $sponsor->id,
            'status' => 'pending',
            'expires_at' => now()->addDays(30),
        ]);

        $response = $this->postJson('/register/check-code', [
            'code' => $sponsorship->sponsorship_code,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'valid' => true,
            'sponsor_name' => $sponsor->full_name,
        ]);
    }

    /** @test */
    public function pin_code_must_be_confirmed()
    {
        $memberType = MemberType::first();

        $data = [
            'first_name' => 'Alice',
            'last_name' => 'Brown',
            'birth_date' => '1980-01-01',
            'phone' => '514-555-0126',
            'email' => 'alice.brown@example.com',
            'address' => '321 Elm St',
            'city' => 'Montreal',
            'province' => 'Québec',
            'postal_code' => 'H4D 4D4',
            'country' => 'Canada',
            'canadian_status_proof' => 'Carte de citoyenneté',
            'member_type_id' => $memberType->id,
            'pin_code' => '1234',
            'pin_code_confirmation' => '5678', // Différent
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionHasErrors(['pin_code_confirmation']);
    }

    /** @test */
    public function email_must_be_unique()
    {
        $memberType = MemberType::first();
        $existingMember = Member::factory()->create(['email' => 'existing@example.com']);

        $data = [
            'first_name' => 'Charlie',
            'last_name' => 'Davis',
            'birth_date' => '1970-01-01',
            'phone' => '514-555-0127',
            'email' => 'existing@example.com', // Email déjà utilisé
            'address' => '654 Maple St',
            'city' => 'Montreal',
            'province' => 'Québec',
            'postal_code' => 'H5E 5E5',
            'country' => 'Canada',
            'canadian_status_proof' => 'Carte de citoyenneté',
            'member_type_id' => $memberType->id,
            'pin_code' => '3456',
            'pin_code_confirmation' => '3456',
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionHasErrors(['email']);
    }
}

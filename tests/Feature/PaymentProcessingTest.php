<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\MemberType;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PaymentProcessingTest extends TestCase
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
        ]);
    }

    /** @test */
    public function payment_service_can_create_adhesion_payment()
    {
        $member = Member::factory()->create();
        $membership = Membership::factory()->create([
            'member_id' => $member->id,
            'status' => 'active',
            'is_active' => true,
        ]);

        $paymentService = new PaymentService();

        $payment = $paymentService->createAdhesionPayment($member, $membership, 50.00);

        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals('adhesion', $payment->type);
        $this->assertEquals(50.00, $payment->amount);
        $this->assertEquals('pending', $payment->status);
        $this->assertNotNull($payment->stripe_payment_intent_id);
    }

    /** @test */
    public function payment_service_can_create_contribution_payment()
    {
        $member = Member::factory()->create();
        $membership = Membership::factory()->create([
            'member_id' => $member->id,
            'status' => 'active',
            'is_active' => true,
        ]);

        $paymentService = new PaymentService();

        $payment = $paymentService->createContributionPayment($member, 25.00, 'Test contribution');

        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals('contribution', $payment->type);
        $this->assertEquals(25.00, $payment->amount);
        $this->assertEquals('pending', $payment->status);
        $this->assertNotNull($payment->stripe_payment_intent_id);
    }

    /** @test */
    public function payment_service_can_confirm_payment()
    {
        $member = Member::factory()->create();
        $membership = Membership::factory()->create([
            'member_id' => $member->id,
            'status' => 'active',
            'is_active' => true,
        ]);

        $payment = Payment::factory()->create([
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'type' => 'adhesion',
            'amount' => 50.00,
            'status' => 'pending',
            'stripe_payment_intent_id' => 'pi_test_123',
        ]);

        $paymentService = new PaymentService();

        // Simuler une confirmation de paiement
        $result = $paymentService->confirmPayment($payment, 'ch_test_123');

        $this->assertTrue($result);
        
        $payment->refresh();
        $this->assertEquals('completed', $payment->status);
        $this->assertNotNull($payment->paid_at);
    }

    /** @test */
    public function payment_service_can_refund_payment()
    {
        $member = Member::factory()->create();
        $payment = Payment::factory()->create([
            'member_id' => $member->id,
            'type' => 'adhesion',
            'amount' => 50.00,
            'status' => 'completed',
            'stripe_charge_id' => 'ch_test_123',
        ]);

        $paymentService = new PaymentService();

        $result = $paymentService->refundPayment($payment, 25.00);

        $this->assertTrue($result);

        // Vérifier qu'un paiement de remboursement a été créé
        $this->assertDatabaseHas('payments', [
            'member_id' => $member->id,
            'type' => 'refund',
            'amount' => 25.00,
            'status' => 'completed',
        ]);
    }

    /** @test */
    public function payment_service_can_get_payment_details()
    {
        $paymentService = new PaymentService();

        $details = $paymentService->getPaymentDetails('pi_test_123');

        // En mode test, cela devrait retourner null car l'ID n'existe pas
        $this->assertNull($details);
    }

    /** @test */
    public function member_can_access_payment_forms()
    {
        $member = Member::factory()->create();
        $membership = Membership::factory()->create([
            'member_id' => $member->id,
            'status' => 'active',
            'is_active' => true,
        ]);

        // Simuler la connexion du membre
        session(['member_id' => $member->id]);

        $response = $this->get('/member/payment/adhesion');

        $response->assertStatus(200);
        $response->assertViewIs('member.payment.adhesion');
    }

    /** @test */
    public function member_cannot_access_payment_forms_without_active_membership()
    {
        $member = Member::factory()->create();

        // Simuler la connexion du membre
        session(['member_id' => $member->id]);

        $response = $this->get('/member/payment/adhesion');

        $response->assertRedirect('/member/dashboard');
        $response->assertSessionHas('error');
    }

    /** @test */
    public function webhook_can_handle_payment_succeeded()
    {
        $member = Member::factory()->create();
        $payment = Payment::factory()->create([
            'member_id' => $member->id,
            'type' => 'adhesion',
            'amount' => 50.00,
            'status' => 'pending',
            'stripe_payment_intent_id' => 'pi_test_123',
        ]);

        $webhookData = [
            'id' => 'evt_test_123',
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_test_123',
                    'status' => 'succeeded',
                    'latest_charge' => 'ch_test_123',
                ],
            ],
        ];

        $response = $this->postJson('/webhook/stripe', $webhookData, [
            'Stripe-Signature' => 'test_signature',
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function webhook_can_handle_payment_failed()
    {
        $member = Member::factory()->create();
        $payment = Payment::factory()->create([
            'member_id' => $member->id,
            'type' => 'adhesion',
            'amount' => 50.00,
            'status' => 'pending',
            'stripe_payment_intent_id' => 'pi_test_123',
        ]);

        $webhookData = [
            'id' => 'evt_test_123',
            'type' => 'payment_intent.payment_failed',
            'data' => [
                'object' => [
                    'id' => 'pi_test_123',
                    'status' => 'requires_payment_method',
                ],
            ],
        ];

        $response = $this->postJson('/webhook/stripe', $webhookData, [
            'Stripe-Signature' => 'test_signature',
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function payment_confirmation_requires_valid_payment_intent()
    {
        $response = $this->postJson('/member/payment/confirm', [
            'payment_intent_id' => 'invalid_id',
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Paiement non trouvé.',
        ]);
    }

    /** @test */
    public function contribution_payment_requires_valid_amount()
    {
        $member = Member::factory()->create();

        // Simuler la connexion du membre
        session(['member_id' => $member->id]);

        $response = $this->postJson('/member/payment/contribution', [
            'amount' => -10.00, // Montant négatif
            'description' => 'Test contribution',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['amount']);
    }
}

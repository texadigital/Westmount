<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\DeathContribution;

class PaymentObserver
{
    public function updated(Payment $payment): void
    {
        // When a payment switches to completed, mark linked death contribution as paid
        if ($payment->wasChanged('status') && $payment->status === 'completed') {
            // By direct link
            $dc = DeathContribution::where('payment_id', $payment->id)->first();

            // Or by metadata
            if (!$dc && is_array($payment->metadata ?? null)) {
                $deathEventId = $payment->metadata['death_event_id'] ?? null;
                if ($deathEventId) {
                    $dc = DeathContribution::where('death_event_id', $deathEventId)
                        ->where('member_id', $payment->member_id)
                        ->orderByDesc('id')
                        ->first();
                }
            }

            if ($dc && $dc->status !== 'paid') {
                $dc->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
        }
    }
}

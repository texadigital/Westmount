<?php

namespace App\Services;

use App\Models\DeathEvent;
use App\Models\DeathContribution;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Setting;
use App\Notifications\DeathEventPublishedNotification;
use Illuminate\Support\Facades\DB;

class DeathEventPublisher
{
    /**
     * Publish a death event: generate contributions for all active members,
     * set 30-day due date, create pending Payment records, and notify members.
     */
    public function publish(DeathEvent $event): void
    {
        DB::transaction(function () use ($event) {
            // Pull all active members with their type amounts
            $members = Member::query()->active()->with('memberType')->get();

            foreach ($members as $member) {
                // Prefer the member type configured amount; fallback to global unit setting
                $configuredAmount = $member->memberType?->death_contribution;
                $defaultUnit = (float) (Setting::get('death_contribution_unit', 10));
                $amount = (float) ($configuredAmount !== null && (float) $configuredAmount > 0
                    ? $configuredAmount
                    : $defaultUnit);
                if ($amount <= 0) {
                    continue; // skip if no configured amount
                }

                // Create pending payment record
                $payment = Payment::create([
                    'member_id' => $member->id,
                    'type' => 'contribution',
                    'amount' => $amount,
                    'penalty_amount' => 0,
                    'total_amount' => $amount,
                    'currency' => 'CAD',
                    'status' => 'pending',
                    'description' => 'Contribution décès: ' . $event->deceased_name,
                    'metadata' => [
                        'death_event_id' => $event->id,
                    ],
                ]);

                // Create death contribution line
                $dc = DeathContribution::create([
                    'death_event_id' => $event->id,
                    'member_id' => $member->id,
                    'amount' => $amount,
                    'currency' => 'CAD',
                    'due_date' => now()->addDays(30)->toDateString(),
                    'status' => 'pending',
                    'payment_id' => $payment->id,
                ]);

                // Notify member via email (queued)
                $member->notify(new DeathEventPublishedNotification($event, $dc));
            }

            $event->update([
                'status' => 'published',
                'published_at' => now(),
            ]);
        });
    }
}

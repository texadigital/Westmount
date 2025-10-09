<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminPaymentReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Payment $payment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $p = $this->payment;
        $member = $p->member;

        return (new MailMessage)
            ->subject('Paiement reçu: #'.$p->id.' - '.$p->type.' '.$p->amount.' '.$p->currency)
            ->greeting('Paiement confirmé')
            ->line('Un paiement vient d\'être confirmé.')
            ->line('Membre: '.($member?->full_name ?? '—').' ('.$p->member_id.')')
            ->line('Type: '.$p->type)
            ->line('Montant: '.number_format((float)$p->amount, 2).' '.$p->currency)
            ->line('Méthode: '.$p->payment_method)
            ->line('Statut: '.$p->status)
            ->when($p->payment_method === 'stripe' && $p->stripe_charge_id, function ($message) use ($p) {
                $message->line('Charge Stripe: '.$p->stripe_charge_id);
            })
            ->when($p->bank_reference, function ($message) use ($p) {
                $message->line('Référence bancaire: '.$p->bank_reference);
            })
            ->when($p->interac_reference, function ($message) use ($p) {
                $message->line('Référence Interac: '.$p->interac_reference);
            })
            ->action('Voir les paiements', url('/member/payments'));
    }
}

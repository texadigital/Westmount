<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Payment $payment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $payment = $this->payment;

        return (new MailMessage)
            ->subject('Paiement reçu - '.$payment->getTypeLabelAttribute())
            ->greeting('Bonjour '.$notifiable->first_name)
            ->line('Nous avons reçu votre paiement.')
            ->line('Montant: '.$payment->getFormattedAmountAttribute())
            ->line('Type: '.$payment->getTypeLabelAttribute())
            ->line('Statut: '.$payment->getStatusLabelAttribute())
            ->when($payment->payment_method === 'stripe', function ($message) {
                $message->line('Méthode: Carte (Stripe)');
            })
            ->when($payment->payment_method === 'interac', function ($message) use ($payment) {
                $message->line('Méthode: Interac')->line('Référence: '.$payment->interac_reference);
            })
            ->when($payment->payment_method === 'bank_transfer', function ($message) use ($payment) {
                $message->line('Méthode: Virement bancaire')->line('Référence: '.$payment->bank_reference);
            })
            ->line('Merci pour votre confiance.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'currency' => $this->payment->currency,
            'type' => $this->payment->type,
            'status' => $this->payment->status,
            'method' => $this->payment->payment_method,
        ];
    }
}

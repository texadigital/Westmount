<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Payment;

class PenaltyAppliedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $member = $this->payment->member;
        $penaltyAmount = number_format($this->payment->penalty_amount, 2);
        $totalAmount = number_format($this->payment->total_amount, 2);
        $overdueDays = $this->payment->overdue_days;

        return (new MailMessage)
            ->subject('Pénalité Appliquée - Association Westmount')
            ->greeting('Bonjour ' . $member->first_name . ',')
            ->line('Une pénalité a été appliquée à votre paiement en retard.')
            ->line('')
            ->line('**Détails du paiement :**')
            ->line('- Type : ' . ucfirst($this->payment->type))
            ->line('- Montant original : ' . number_format($this->payment->amount, 2) . ' CAD')
            ->line('- Pénalité : ' . $penaltyAmount . ' CAD')
            ->line('- Montant total : ' . $totalAmount . ' CAD')
            ->line('- Jours de retard : ' . $overdueDays)
            ->line('')
            ->line('**Raison :**')
            ->line($this->payment->penalty_reason)
            ->line('')
            ->line('**Prochaines étapes :**')
            ->line('1. Effectuez le paiement du montant total incluant la pénalité')
            ->line('2. Les pénalités peuvent augmenter avec le temps')
            ->line('3. Contactez-nous si vous avez des questions')
            ->action('Effectuer le Paiement', url('/member/payment'))
            ->line('Pour éviter de futures pénalités, effectuez vos paiements avant la date d\'échéance.')
            ->salutation('Cordialement, L\'équipe Association Westmount');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'penalty_amount' => $this->payment->penalty_amount,
            'total_amount' => $this->payment->total_amount,
            'overdue_days' => $this->payment->overdue_days,
            'type' => 'penalty_applied',
            'message' => 'Pénalité appliquée à votre paiement',
        ];
    }
}

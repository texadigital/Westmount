<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Membership;

class PaymentOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $membership;

    /**
     * Create a new notification instance.
     */
    public function __construct(Membership $membership)
    {
        $this->membership = $membership;
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
        $member = $this->membership->member;
        $overdueDays = $this->membership->overdue_days;
        $amountDue = $this->membership->amount_due;

        return (new MailMessage)
            ->subject('Paiement en Retard - Association Westmount')
            ->greeting('Bonjour ' . $member->first_name . ',')
            ->line('Nous vous informons que votre paiement est en retard de ' . $overdueDays . ' jour(s).')
            ->line('Montant dû : ' . number_format($amountDue, 2) . ' CAD')
            ->line('Date d\'échéance : ' . $this->membership->next_payment_due->format('d/m/Y'))
            ->action('Effectuer le Paiement', url('/member/payment'))
            ->line('Merci de régulariser votre situation dès que possible.')
            ->line('Pour toute question, n\'hésitez pas à nous contacter.')
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
            'membership_id' => $this->membership->id,
            'member_id' => $this->membership->member_id,
            'overdue_days' => $this->membership->overdue_days,
            'amount_due' => $this->membership->amount_due,
            'message' => 'Paiement en retard de ' . $this->membership->overdue_days . ' jour(s)',
            'type' => 'payment_overdue',
        ];
    }
}

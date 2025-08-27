<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Contribution;

class ContributionOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $contribution;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contribution $contribution)
    {
        $this->contribution = $contribution;
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
        $member = $this->contribution->member;
        $deceasedMember = $this->contribution->deceasedMember;
        $overdueDays = now()->diffInDays($this->contribution->due_date);

        return (new MailMessage)
            ->subject('Contribution en Retard - Association Westmount')
            ->greeting('Bonjour ' . $member->first_name . ',')
            ->line('Nous vous informons que votre contribution de décès est en retard de ' . $overdueDays . ' jour(s).')
            ->line('Membre décédé : ' . $deceasedMember->full_name)
            ->line('Montant dû : ' . number_format($this->contribution->amount, 2) . ' CAD')
            ->line('Date d\'échéance : ' . $this->contribution->due_date->format('d/m/Y'))
            ->action('Effectuer le Paiement', url('/member/payment/contribution'))
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
            'contribution_id' => $this->contribution->id,
            'deceased_member_name' => $this->contribution->deceasedMember->full_name,
            'amount' => $this->contribution->amount,
            'due_date' => $this->contribution->due_date->format('Y-m-d'),
            'overdue_days' => now()->diffInDays($this->contribution->due_date),
            'type' => 'contribution_overdue',
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\DeathEvent;
use App\Models\DeathContribution;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeathEventPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public DeathEvent $event,
        public ?DeathContribution $contribution = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $amount = $this->contribution?->amount;
        $due = optional($this->contribution?->due_date)?->format('Y-m-d');

        $mail = (new MailMessage)
            ->subject('Avis de contribution - Décès: ' . $this->event->deceased_name)
            ->greeting('Bonjour ' . ($notifiable->full_name ?? ''))
            ->line("Nous vous informons du décès de: {$this->event->deceased_name}.")
            ->line('Nous comptons sur la solidarité de tous les membres.');

        if ($amount) {
            $mail->line("Montant à payer: " . number_format($amount, 2) . ' CAD');
        }
        if ($due) {
            $mail->line("Échéance: {$due} (30 jours)");
        }

        if ($this->event->description) {
            $mail->line('Informations:')->line($this->event->description);
        }

        return $mail->action('Payer ma contribution', url(route('member.login')))
            ->line('Merci de votre soutien.');
    }
}

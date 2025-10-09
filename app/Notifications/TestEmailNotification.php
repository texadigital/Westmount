<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Test Email - Association Westmount')
            ->greeting('Bonjour')
            ->line("Ceci est un email de test envoyé depuis la page Système > Paramètres.")
            ->line('Si vous recevez ce message, la configuration SMTP est fonctionnelle.');
    }
}

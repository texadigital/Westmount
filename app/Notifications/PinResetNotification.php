<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Member;

class PinResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $member;
    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(Member $member, string $token)
    {
        $this->member = $member;
        $this->token = $token;
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
        $resetUrl = route('public.account-recovery.reset', [
            'token' => $this->token,
            'email' => $this->member->email
        ]);

        return (new MailMessage)
            ->subject('Réinitialisation de votre Code PIN - Association Westmount')
            ->greeting('Bonjour ' . $this->member->first_name . ',')
            ->line('Vous avez demandé la réinitialisation de votre code PIN pour votre compte Association Westmount.')
            ->line('**Détails du compte :**')
            ->line('- Numéro de membre : ' . $this->member->member_number)
            ->line('- Nom : ' . $this->member->full_name)
            ->line('')
            ->line('Cliquez sur le bouton ci-dessous pour réinitialiser votre code PIN :')
            ->action('Réinitialiser mon Code PIN', $resetUrl)
            ->line('Ce lien est valide pendant 24 heures.')
            ->line('Si vous n\'avez pas demandé cette réinitialisation, ignorez cet email.')
            ->line('Pour votre sécurité, ne partagez jamais votre code PIN avec d\'autres personnes.')
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
            'member_id' => $this->member->id,
            'member_number' => $this->member->member_number,
            'type' => 'pin_reset_request',
            'message' => 'Demande de réinitialisation du code PIN',
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Member;

class WelcomeMemberNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $member;

    /**
     * Create a new notification instance.
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
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
        return (new MailMessage)
            ->subject('Bienvenue à l\'Association Westmount !')
            ->greeting('Bienvenue ' . $this->member->first_name . ' !')
            ->line('Nous sommes ravis de vous accueillir au sein de l\'Association d\'entraide et de solidarité Westmount.')
            ->line('Votre numéro de membre : **' . $this->member->member_number . '**')
            ->line('Type de membre : ' . $this->member->memberType->name)
            ->line('Frais d\'adhésion : ' . number_format($this->member->memberType->adhesion_fee, 2) . ' CAD')
            ->line('Contribution décès : ' . number_format($this->member->memberType->death_contribution, 2) . ' CAD')
            ->action('Accéder à votre Espace Membre', url('/member/login'))
            ->line('Vous pouvez vous connecter avec votre numéro de membre et votre code PIN.')
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
            'member_id' => $this->member->id,
            'member_number' => $this->member->member_number,
            'message' => 'Bienvenue dans l\'association !',
            'type' => 'welcome_member',
        ];
    }
}

     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'member_id' => $this->member->id,
            'member_number' => $this->member->member_number,
            'message' => 'Bienvenue dans l\'association !',
            'type' => 'welcome_member',
        ];
    }
}

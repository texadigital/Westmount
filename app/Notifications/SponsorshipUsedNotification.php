<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Sponsorship;
use App\Models\Member;

class SponsorshipUsedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $sponsorship;
    public $newMember;

    /**
     * Create a new notification instance.
     */
    public function __construct(Sponsorship $sponsorship, Member $newMember)
    {
        $this->sponsorship = $sponsorship;
        $this->newMember = $newMember;
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
            ->subject('Votre parrainage a été utilisé - Association Westmount')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Nous avons le plaisir de vous informer que votre code de parrainage a été utilisé avec succès !')
            ->line('Nouveau membre parrainé :')
            ->line('• Nom : ' . $this->newMember->full_name)
            ->line('• Email : ' . $this->newMember->email)
            ->line('• Type de membre : ' . $this->newMember->memberType->name)
            ->line('• Date d\'inscription : ' . $this->newMember->created_at->format('d/m/Y'))
            ->action('Voir mes parrainages', url('/member/sponsorship'))
            ->line('Merci de contribuer à la croissance de notre association !')
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
            'type' => 'sponsorship_used',
            'sponsorship_id' => $this->sponsorship->id,
            'new_member_id' => $this->newMember->id,
            'new_member_name' => $this->newMember->full_name,
            'message' => 'Votre parrainage a été utilisé par ' . $this->newMember->full_name,
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Organization;
use App\Models\Member;

class OrganizationWelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $organization;
    public $members;

    /**
     * Create a new notification instance.
     */
    public function __construct(Organization $organization, array $members)
    {
        $this->organization = $organization;
        $this->members = $members;
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
        $memberCount = count($this->members);
        $totalFees = $this->organization->total_fees;

        return (new MailMessage)
            ->subject('Bienvenue à l\'Association Westmount - ' . $this->organization->name)
            ->greeting('Bienvenue ' . $this->organization->contact_person . ' !')
            ->line('Nous sommes ravis d\'accueillir **' . $this->organization->name . '** au sein de l\'Association d\'entraide et de solidarité Westmount.')
            ->line('**Détails de l\'organisation :**')
            ->line('- Nom : ' . $this->organization->name)
            ->line('- Numéro d\'entreprise : ' . $this->organization->business_number)
            ->line('- Nombre de membres : ' . $memberCount)
            ->line('- Frais totaux : ' . number_format($totalFees, 2) . ' CAD')
            ->line('')
            ->line('**Membres enregistrés :**')
            ->line($this->getMembersList())
            ->line('')
            ->line('Chaque membre a reçu ses identifiants de connexion par email séparément.')
            ->line('Vous pouvez gérer votre organisation via l\'espace membre de chaque membre.')
            ->action('Accéder à l\'Espace Membre', url('/member/login'))
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
            'organization_id' => $this->organization->id,
            'organization_name' => $this->organization->name,
            'member_count' => count($this->members),
            'total_fees' => $this->organization->total_fees,
            'message' => 'Organisation ' . $this->organization->name . ' enregistrée avec succès',
        ];
    }

    /**
     * Get the members list for the email
     */
    private function getMembersList(): string
    {
        $list = '';
        foreach ($this->members as $member) {
            $list .= '- ' . $member->full_name . ' (' . $member->member_number . ') - ' . $member->memberType->name . "\n";
        }
        return $list;
    }
}

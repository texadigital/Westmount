<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InteracInstructionsNotification extends Notification
{
    use Queueable;

    protected $payment;

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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $interacInfo = [
            'email' => config('services.interac.email', 'paiements@associationwestmount.com'),
            'name' => config('services.interac.name', 'Association Westmount'),
            'security_question' => config('services.interac.security_question', 'Quel est le nom de l\'association?'),
            'security_answer' => config('services.interac.security_answer', 'Westmount'),
        ];

        return (new MailMessage)
            ->subject('Instructions de paiement Interac - Association Westmount')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Merci pour votre inscription à l\'Association Westmount.')
            ->line('**Instructions de paiement Interac e-Transfer:**')
            ->line('')
            ->line('**Montant à payer:** ' . number_format($this->payment->total_amount, 2) . ' CAD')
            ->line('**Référence de paiement:** ' . $this->payment->interac_reference)
            ->line('')
            ->line('**Étapes pour effectuer le paiement:**')
            ->line('1. Connectez-vous à votre service bancaire en ligne')
            ->line('2. Sélectionnez "Interac e-Transfer" ou "Virement Interac"')
            ->line('3. Envoyez l\'argent à: **' . $interacInfo['email'] . '**')
            ->line('4. Nom du destinataire: **' . $interacInfo['name'] . '**')
            ->line('5. Montant: **' . number_format($this->payment->total_amount, 2) . ' CAD**')
            ->line('6. Question de sécurité: **' . $interacInfo['security_question'] . '**')
            ->line('7. Réponse: **' . $interacInfo['security_answer'] . '**')
            ->line('8. Message (optionnel): Référence ' . $this->payment->interac_reference)
            ->line('')
            ->line('**Important:**')
            ->line('- Le paiement sera confirmé manuellement par notre équipe')
            ->line('- Vous recevrez un email de confirmation une fois le paiement traité')
            ->line('- Conservez cette référence: ' . $this->payment->interac_reference)
            ->line('')
            ->line('Si vous avez des questions, n\'hésitez pas à nous contacter.')
            ->salutation('Cordialement, L\'équipe de l\'Association Westmount');
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
            'amount' => $this->payment->total_amount,
            'interac_reference' => $this->payment->interac_reference,
        ];
    }
}

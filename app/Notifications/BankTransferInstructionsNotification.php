<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Payment;
use App\Services\BankTransferService;

class BankTransferInstructionsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $payment;
    public $bankingInfo;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->bankingInfo = (new BankTransferService())->getBankingInfo();
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
        $amount = number_format($this->payment->amount, 2);
        $reference = $this->payment->bank_reference;

        return (new MailMessage)
            ->subject('Instructions de Virement Bancaire - Association Westmount')
            ->greeting('Bonjour ' . $member->first_name . ',')
            ->line('Vous avez choisi de payer par virement bancaire.')
            ->line('**Détails du paiement :**')
            ->line('- Montant : ' . $amount . ' CAD')
            ->line('- Référence : ' . $reference)
            ->line('- Description : ' . $this->payment->description)
            ->line('')
            ->line('**Instructions de virement :**')
            ->line('1. Connectez-vous à votre service bancaire en ligne')
            ->line('2. Effectuez un virement vers le compte suivant :')
            ->line('')
            ->line('**Informations bancaires :**')
            ->line('- Banque : ' . $this->bankingInfo['bank_name'])
            ->line('- Titulaire : ' . $this->bankingInfo['account_holder'])
            ->line('- Numéro de compte : ' . $this->bankingInfo['account_number'])
            ->line('- Numéro de transit : ' . $this->bankingInfo['transit_number'])
            ->line('- Numéro d\'institution : ' . $this->bankingInfo['institution_number'])
            ->line('')
            ->line('**Important :**')
            ->line('- Incluez la référence "' . $reference . '" dans la description du virement')
            ->line('- Le paiement sera confirmé dans les 2-3 jours ouvrables')
            ->line('- Vous recevrez un reçu par email une fois le paiement confirmé')
            ->line('')
            ->line('**Pour toute question :**')
            ->line('Contactez-nous à contact@associationwestmount.com')
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
            'amount' => $this->payment->amount,
            'reference' => $this->payment->bank_reference,
            'type' => 'bank_transfer_instructions',
            'message' => 'Instructions de virement bancaire envoyées',
        ];
    }
}

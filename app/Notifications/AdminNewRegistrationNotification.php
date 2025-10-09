<?php

namespace App\Notifications;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNewRegistrationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Member $member)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $m = $this->member;

        return (new MailMessage)
            ->subject('Nouvelle inscription membre: '.$m->full_name)
            ->greeting('Nouvelle inscription')
            ->line('Un nouveau membre vient de s\'inscrire.')
            ->line('Nom: '.$m->full_name)
            ->line('Numéro de membre: '.$m->member_number)
            ->line('Email: '.$m->email)
            ->line('Téléphone: '.$m->phone)
            ->line('Type de membre: '.($m->memberType->name ?? '—'))
            ->action('Voir le membre', url('/member/dashboard'));
    }
}

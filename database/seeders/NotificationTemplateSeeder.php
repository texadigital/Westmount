<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Welcome Member Email',
                'type' => 'email',
                'event' => 'member_welcome',
                'subject' => 'Bienvenue à l\'Association Westmount - {{member_name}}',
                'body' => 'Bonjour {{member_name}},

Bienvenue à l\'Association Westmount !

Votre adhésion a été confirmée avec succès.
Numéro de membre : {{member_number}}
Code PIN : {{pin_code}}

Vous pouvez maintenant accéder à votre espace membre à l\'adresse : {{member_dashboard_url}}

Cordialement,
L\'équipe de l\'Association Westmount',
                'variables' => ['member_name', 'member_number', 'pin_code', 'member_dashboard_url'],
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'Payment Reminder Email',
                'type' => 'email',
                'event' => 'payment_reminder',
                'subject' => 'Rappel de paiement - {{member_name}}',
                'body' => 'Bonjour {{member_name}},

Ceci est un rappel amical concernant votre paiement en attente.

Montant dû : {{amount_due}} CAD
Date d\'échéance : {{due_date}}
Type de paiement : {{payment_type}}

Veuillez effectuer votre paiement dès que possible pour éviter des frais de retard.

Cordialement,
L\'équipe de l\'Association Westmount',
                'variables' => ['member_name', 'amount_due', 'due_date', 'payment_type'],
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'Payment Overdue Email',
                'type' => 'email',
                'event' => 'payment_overdue',
                'subject' => 'Paiement en retard - {{member_name}}',
                'body' => 'Bonjour {{member_name}},

Votre paiement est maintenant en retard.

Montant dû : {{amount_due}} CAD
Jours de retard : {{days_overdue}}
Frais de retard : {{penalty_amount}} CAD

Veuillez effectuer votre paiement immédiatement pour éviter la suspension de votre adhésion.

Cordialement,
L\'équipe de l\'Association Westmount',
                'variables' => ['member_name', 'amount_due', 'days_overdue', 'penalty_amount'],
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'Sponsorship Used Email',
                'type' => 'email',
                'event' => 'sponsorship_used',
                'subject' => 'Votre code de parrainage a été utilisé - {{sponsor_name}}',
                'body' => 'Bonjour {{sponsor_name}},

Votre code de parrainage a été utilisé avec succès !

Nouveau membre : {{new_member_name}}
Code utilisé : {{sponsorship_code}}
Date d\'utilisation : {{used_date}}

Merci d\'avoir parrainé un nouveau membre de l\'Association Westmount.

Cordialement,
L\'équipe de l\'Association Westmount',
                'variables' => ['sponsor_name', 'new_member_name', 'sponsorship_code', 'used_date'],
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'Contribution Due Email',
                'type' => 'email',
                'event' => 'contribution_due',
                'subject' => 'Contribution de décès due - {{member_name}}',
                'body' => 'Bonjour {{member_name}},

Une contribution de décès est due.

Membre décédé : {{deceased_member_name}}
Montant : {{contribution_amount}} CAD
Date limite : {{due_date}}

Veuillez effectuer votre contribution dans les plus brefs délais.

Cordialement,
L\'équipe de l\'Association Westmount',
                'variables' => ['member_name', 'deceased_member_name', 'contribution_amount', 'due_date'],
                'is_active' => true,
                'is_system' => true,
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::create($template);
        }
    }
}

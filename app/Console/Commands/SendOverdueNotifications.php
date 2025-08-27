<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Membership;
use App\Notifications\PaymentOverdueNotification;
use Carbon\Carbon;

class SendOverdueNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-overdue {--days=1 : Nombre de jours de retard minimum}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer les notifications de paiements en retard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minDays = $this->option('days');
        
        $this->info("Recherche des adhésions en retard de {$minDays} jour(s) ou plus...");
        
        $overdueMemberships = Membership::where('status', 'overdue')
            ->where('overdue_days', '>=', $minDays)
            ->where('is_active', true)
            ->with('member')
            ->get();
        
        $this->info("Trouvé {$overdueMemberships->count()} adhésion(s) en retard.");
        
        $sentCount = 0;
        
        foreach ($overdueMemberships as $membership) {
            try {
                $member = $membership->member;
                
                // Vérifier si une notification a déjà été envoyée aujourd'hui
                $todayNotifications = $member->notifications()
                    ->where('type', PaymentOverdueNotification::class)
                    ->whereDate('created_at', Carbon::today())
                    ->count();
                
                if ($todayNotifications === 0) {
                    $member->notify(new PaymentOverdueNotification($membership));
                    $sentCount++;
                    
                    $this->line("Notification envoyée à {$member->full_name} (Retard: {$membership->overdue_days} jours)");
                } else {
                    $this->line("Notification déjà envoyée aujourd'hui à {$member->full_name}");
                }
            } catch (\Exception $e) {
                $this->error("Erreur lors de l'envoi à {$membership->member->full_name}: " . $e->getMessage());
            }
        }
        
        $this->info("Terminé ! {$sentCount} notification(s) envoyée(s).");
        
        return 0;
    }
}

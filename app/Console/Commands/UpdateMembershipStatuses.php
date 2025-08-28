<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Membership;
use Carbon\Carbon;

class UpdateMembershipStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memberships:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mettre à jour automatiquement les statuts des adhésions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mise à jour des statuts des adhésions...');
        
        $updatedCount = 0;
        $overdueCount = 0;
        $lapsedCount = 0;
        
        // Récupérer toutes les adhésions actives
        $memberships = Membership::where('is_active', true)->get();
        
        foreach ($memberships as $membership) {
            $oldStatus = $membership->status;
            $membership->updateStatus();
            
            if ($membership->status !== $oldStatus) {
                $updatedCount++;
                
                if ($membership->status === 'overdue') {
                    $overdueCount++;
                    $this->line("Adhésion {$membership->id} mise en retard (Membre: {$membership->member->full_name})");
                } elseif ($membership->status === 'lapsed') {
                    $lapsedCount++;
                    $this->line("Adhésion {$membership->id} marquée comme caduque (Membre: {$membership->member->full_name})");
                }
            }
        }
        
        $this->info("Terminé ! {$updatedCount} adhésion(s) mise(s) à jour.");
        $this->info("- {$overdueCount} mise(s) en retard");
        $this->info("- {$lapsedCount} marquée(s) comme caduque(s)");
        
        return 0;
    }
}
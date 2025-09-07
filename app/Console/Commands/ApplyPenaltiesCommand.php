<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PenaltyService;

class ApplyPenaltiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'penalties:apply {--dry-run : Afficher ce qui serait fait sans l\'exécuter}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Appliquer les pénalités aux paiements en retard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Application des pénalités aux paiements en retard...');
        
        if ($this->option('dry-run')) {
            $this->warn('Mode DRY-RUN activé - aucune modification ne sera effectuée');
        }

        $penaltyService = new PenaltyService();
        
        try {
            $results = $penaltyService->applyPenalties();
            
            $this->info("Traitement terminé :");
            $this->line("- Paiements traités : {$results['processed']}");
            $this->line("- Pénalités appliquées : {$results['penalties_applied']}");
            
            if (!empty($results['errors'])) {
                $this->error("Erreurs rencontrées :");
                foreach ($results['errors'] as $error) {
                    $this->line("- {$error}");
                }
            }
            
            if ($results['penalties_applied'] > 0) {
                $this->info("✅ {$results['penalties_applied']} pénalité(s) appliquée(s) avec succès");
            } else {
                $this->info("ℹ️  Aucune pénalité à appliquer");
            }
            
        } catch (\Exception $e) {
            $this->error("Erreur lors de l'application des pénalités : " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
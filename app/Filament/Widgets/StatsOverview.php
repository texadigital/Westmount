<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\Fund;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalMembers = Member::active()->count();
        $activeMemberships = Membership::active()->count();
        $overdueMemberships = Membership::overdue()->count();
        $lapsedMemberships = Membership::lapsed()->count();
        
        $totalFunds = Fund::active()->sum('current_balance');
        $totalContributions = Fund::active()->sum('total_contributions');
        $totalDistributions = Fund::active()->sum('total_distributions');
        
        $monthlyPayments = Payment::completed()
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        return [
            Stat::make('Total Membres', $totalMembers)
                ->description('Membres actifs de l\'association')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Adhésions Actives', $activeMemberships)
                ->description('Membres en règle')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Membres en Retard', $overdueMemberships)
                ->description('Paiements en retard')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),

            Stat::make('Membres Caducs', $lapsedMemberships)
                ->description('Adhésions expirées')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Fonds Disponibles', number_format($totalFunds, 2) . ' CAD')
                ->description('Solde total des fonds')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Contributions Totales', number_format($totalContributions, 2) . ' CAD')
                ->description('Total des contributions reçues')
                ->descriptionIcon('heroicon-m-arrow-up')
                ->color('info'),

            Stat::make('Distributions Totales', number_format($totalDistributions, 2) . ' CAD')
                ->description('Total des distributions effectuées')
                ->descriptionIcon('heroicon-m-arrow-down')
                ->color('info'),

            Stat::make('Paiements du Mois', number_format($monthlyPayments, 2) . ' CAD')
                ->description('Paiements reçus ce mois-ci')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),
        ];
    }
}

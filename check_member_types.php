<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VÉRIFICATION DES TYPES DE MEMBRES ===\n\n";

$memberTypes = \App\Models\MemberType::all();

foreach ($memberTypes as $type) {
    echo "Type: {$type->name}\n";
    echo "  - Frais d'adhésion: {$type->adhesion_fee} CAD\n";
    echo "  - Contribution décès: {$type->death_contribution} CAD\n";
    echo "  - Total: " . ($type->adhesion_fee + $type->death_contribution) . " CAD\n";
    echo "  - Actif: " . ($type->is_active ? 'Oui' : 'Non') . "\n\n";
}

echo "=== VÉRIFICATION DES PAIEMENTS EN COURS ===\n\n";

$payments = \App\Models\Payment::where('status', 'pending')->get();

foreach ($payments as $payment) {
    echo "Paiement ID: {$payment->id}\n";
    echo "  - Membre: {$payment->member->full_name}\n";
    echo "  - Type: {$payment->type}\n";
    echo "  - Montant: {$payment->amount} CAD\n";
    echo "  - Pénalité: {$payment->penalty_amount} CAD\n";
    echo "  - Total: {$payment->total_amount} CAD\n";
    echo "  - Méthode: {$payment->payment_method}\n\n";
}

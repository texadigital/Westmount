<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Member;

class MemberAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si le membre est connecté via la session
        if (!$request->session()->has('member_id')) {
            return redirect()->route('member.login')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Récupérer le membre depuis la base de données
        $member = Member::find($request->session()->get('member_id'));
        
        if (!$member || !$member->is_active) {
            // Membre non trouvé ou inactif, déconnecter
            $request->session()->forget('member_id');
            return redirect()->route('member.login')
                ->with('error', 'Votre compte a été désactivé ou n\'existe plus.');
        }

        // Ajouter le membre à la requête pour y accéder dans les contrôleurs
        $request->attributes->set('current_member', $member);

        return $next($request);
    }
}

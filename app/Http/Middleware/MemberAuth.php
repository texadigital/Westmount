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
        // Debug logging for middleware
        \Log::info('MemberAuth middleware', [
            'url' => $request->url(),
            'method' => $request->method(),
            'session_id' => $request->session()->getId(),
            'has_member_id' => $request->session()->has('member_id'),
            'member_id' => $request->session()->get('member_id'),
            'expects_json' => $request->expectsJson(),
            'ajax' => $request->ajax()
        ]);

        // Vérifier si le membre est connecté via la session
        if (!$request->session()->has('member_id')) {
            \Log::warning('No member_id in session', [
                'url' => $request->url(),
                'session_id' => $request->session()->getId()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Veuillez vous connecter pour accéder à cette page.'], 401);
            }
            
            return redirect()->route('member.login')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Récupérer le membre depuis la base de données
        $member = Member::find($request->session()->get('member_id'));
        
        if (!$member || !$member->is_active) {
            // Membre non trouvé ou inactif, déconnecter
            \Log::warning('Member not found or inactive', [
                'member_id' => $request->session()->get('member_id'),
                'member_found' => $member ? true : false,
                'member_active' => $member ? $member->is_active : false
            ]);
            
            $request->session()->forget('member_id');
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Votre compte a été désactivé ou n\'existe plus.'], 401);
            }
            
            return redirect()->route('member.login')
                ->with('error', 'Votre compte a été désactivé ou n\'existe plus.');
        }

        // Ajouter le membre à la requête pour y accéder dans les contrôleurs
        $request->attributes->set('current_member', $member);

        \Log::info('MemberAuth middleware passed', [
            'member_id' => $member->id,
            'member_name' => $member->first_name . ' ' . $member->last_name
        ]);

        return $next($request);
    }
}

<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use App\Models\MemberType;
use App\Models\Setting;
use Illuminate\Http\Request;

class DeathContributionsController extends Controller
{
    public function index()
    {
        $pageContent = PageContent::where('page', 'death_contributions')->first();

        // Dynamic rates: load active member types and their death contribution amounts
        $memberTypes = MemberType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['name', 'death_contribution']);

        $dc = [
            'step1_title' => Setting::get('dc_step1_title', 'Contribution en cas de décès'),
            'step1_body' => Setting::get('dc_step1_body', 'Chaque membre contribue selon sa catégorie'),
            'step2_title' => Setting::get('dc_step2_title', 'Fonds de solidarité'),
            'step2_body' => Setting::get('dc_step2_body', 'Les contributions sont versées dans un fonds de solidarité'),
            'step3_title' => Setting::get('dc_step3_title', 'Aide financière'),
            'step3_body' => Setting::get('dc_step3_body', 'En cas de décès, la famille reçoit une aide financière'),
        ];

        // Configurable unit contribution (default $10)
        $unitContribution = (float) Setting::get('death_contribution_unit', 10);

        return view('public.death-contributions', compact('pageContent', 'memberTypes', 'dc', 'unitContribution'));
    }
}

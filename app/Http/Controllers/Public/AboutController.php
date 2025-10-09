<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use App\Models\TeamMember;
use App\Models\Setting;

class AboutController extends Controller
{
    /**
     * Display the about page
     */
    public function index()
    {
        // Get dynamic content for about page
        $pageContent = PageContent::getPageContent('about');

        // Load team members managed from admin dashboard (gracefully handle missing table)
        try {
            $teamMembers = TeamMember::query()
                ->where('is_active', true)
                ->orderBy('order_column')
                ->orderBy('name')
                ->get();
        } catch (\Illuminate\Database\QueryException $e) {
            // Table may not be migrated yet; show page without team to avoid 500
            $teamMembers = collect();
        }

        // Load About page settings with sensible defaults
        $aboutSettings = [
            'history_title' => Setting::get('about_history_title', 'Notre Histoire'),
            'history_body' => Setting::get('about_history_body', "L'Association Westmount a été fondée en 2024 par un groupe de familles montréalaises qui souhaite promouvoir un système de solidarité pour optimiser les possibilités d'entraide lors du départ d’un proche parent."),
            'history_image' => Setting::get('about_history_image'),
            'mission_text' => Setting::get('about_mission_text', "Offrir un soutien financier et moral à nos membres lors des décès d'un proche, en créant un réseau d'entraide basé sur la solidarité et la confiance mutuelle."),
            'vision_text' => Setting::get('about_vision_text', "Être une référence en matière d'associations d'entraide au Canada, en développant un modèle durable et transparent."),
            'values_text' => Setting::get('about_values_text', 'Solidarité, Transparence, Respect, Intégrité et Compassion guident nos actions.'),
            'stats_members_value' => Setting::get('about_stats_members_value', '1000+'),
            'stats_members_label' => Setting::get('about_stats_members_label', 'Membres actifs'),
            'stats_contrib_value' => Setting::get('about_stats_contrib_value', '$2M+'),
            'stats_contrib_label' => Setting::get('about_stats_contrib_label', 'Contributions versées'),
            'stats_years_value' => Setting::get('about_stats_years_value', '25+'),
            'stats_years_label' => Setting::get('about_stats_years_label', "Années d'expérience"),
            'stats_satisfaction_value' => Setting::get('about_stats_satisfaction_value', '100%'),
            'stats_satisfaction_label' => Setting::get('about_stats_satisfaction_label', 'Satisfaction'),
        ];

        return view('public.about', [
            'pageContent' => $pageContent,
            'teamMembers' => $teamMembers,
            'about' => $aboutSettings,
        ]);
    }
}

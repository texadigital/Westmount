<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\Fund;
use App\Models\Membership;
use App\Models\PageContent;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        try {
            // Get statistics for the home page
            $stats = [
                'total_members' => Member::where('is_active', true)->count(),
                'total_funds' => Fund::where('is_active', true)->sum('current_balance'),
                'years_active' => 25, // Static value for now
            ];

            // Get member types for pricing display
            $memberTypes = MemberType::active()->get();

            // Get dynamic content for home page (with fallback)
            $content = null;
            try {
                $content = PageContent::getPageContent('home');
            } catch (\Exception $e) {
                // If PageContent table doesn't exist or has issues, use default content
                $content = (object) [
                    'title' => 'Solidarité & Entraide',
                    'content' => "L'Association Westmount Canada est une communauté solidaire et d'entraide qui vise à apporter un soutien à la famille d'un membre décédé. Ce soutien inclut notamment une aide financière pour aider la famille à faire face aux défis quotidiens."
                ];
            }

            return view('public.home', compact('stats', 'memberTypes', 'content'));
        } catch (\Exception $e) {
            // Fallback if there are any database issues
            $stats = [
                'total_members' => 500,
                'total_funds' => 50000,
                'years_active' => 25,
            ];

            $memberTypes = collect([]);
            $content = (object) [
                'title' => 'Solidarité & Entraide',
                'content' => "L'Association Westmount Canada est une communauté solidaire et d'entraide qui vise à apporter un soutien à la famille d'un membre décédé. Ce soutien inclut notamment une aide financière pour aider la famille à faire face aux défis quotidiens."
            ];

            return view('public.home', compact('stats', 'memberTypes', 'content'));
        }
    }
}

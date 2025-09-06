<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\Fund;
use App\Models\Membership;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        // Get statistics for the home page
        $stats = [
            'total_members' => Member::where('is_active', true)->count(),
            'total_funds' => Fund::where('is_active', true)->sum('current_balance'),
            'years_active' => 25, // Static value for now
        ];

        // Get member types for pricing display
        $memberTypes = MemberType::active()->get();

        return view('public.home', compact('stats', 'memberTypes'));
    }
}

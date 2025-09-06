<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;

class DeathContributionsController extends Controller
{
    public function index()
    {
        $pageContent = PageContent::where('page', 'death_contributions')->first();
        
        return view('public.death-contributions', compact('pageContent'));
    }
}

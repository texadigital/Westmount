<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;

class SponsorshipController extends Controller
{
    public function index()
    {
        $pageContent = PageContent::where('page', 'sponsorship')->first();
        
        return view('public.sponsorship', compact('pageContent'));
    }
}

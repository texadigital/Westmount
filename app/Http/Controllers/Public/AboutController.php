<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageContent;

class AboutController extends Controller
{
    /**
     * Display the about page
     */
    public function index()
    {
        // Get dynamic content for about page
        $pageContent = PageContent::getPageContent('about');
        
        return view('public.about', compact('pageContent'));
    }
}

<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index()
    {
        $pageContent = PageContent::where('page', 'faq')->first();
        
        return view('public.faq', compact('pageContent'));
    }
}

<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;

class TechnicalSupportController extends Controller
{
    public function index()
    {
        $pageContent = PageContent::where('page', 'technical_support')->first();
        
        return view('public.technical-support', compact('pageContent'));
    }
}

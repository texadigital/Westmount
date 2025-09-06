<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index()
    {
        $pageContent = PageContent::where('page', 'services')->first();
        
        return view('public.services', compact('pageContent'));
    }
}

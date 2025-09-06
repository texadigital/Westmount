<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;

class OnlineManagementController extends Controller
{
    public function index()
    {
        $pageContent = PageContent::where('page', 'online_management')->first();
        
        return view('public.online-management', compact('pageContent'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MobileApp;
use App\Models\Category;
use App\Models\Analytic;

class DashboardController extends Controller
{
    public function index()
    {
        $totalApps = MobileApp::count();
        $totalCategories = Category::count();
        $totalDownloads = Analytic::where('event_type', 'download')->count();
        $totalViews = Analytic::where('event_type', 'view')->count();
        $recentApps = MobileApp::with('category')->latest()->limit(5)->get();
        $recentEvents = Analytic::with('mobileApp')->latest()->limit(6)->get();

        return view('admin.dashboard', compact(
            'totalApps', 
            'totalCategories', 
            'totalDownloads', 
            'totalViews', 
            'recentApps', 
            'recentEvents'
        ));
    }
}

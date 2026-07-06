<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MobileApp;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalApps = MobileApp::count();
        $totalCategories = Category::count();
        $totalDownloads = 1205; // Mock data for now since we haven't implemented downloads tracking

        return view('admin.dashboard', compact('totalApps', 'totalCategories', 'totalDownloads'));
    }
}

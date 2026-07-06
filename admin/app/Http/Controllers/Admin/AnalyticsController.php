<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Analytic;
use App\Models\MobileApp;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $totalDownloads = Analytic::where('event_type', 'download')->count();
        $totalViews = Analytic::where('event_type', 'view')->count();

        // Most downloaded app
        $topAppStats = Analytic::where('event_type', 'download')
            ->select('mobile_app_id', DB::raw('count(*) as total'))
            ->groupBy('mobile_app_id')
            ->orderByDesc('total')
            ->first();
            
        $topApp = $topAppStats ? MobileApp::find($topAppStats->mobile_app_id) : null;

        // Data for Downloads Over Time (Last 7 Days)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->format('M d');
            
            $count = Analytic::where('event_type', 'download')
                ->whereDate('created_at', $date)
                ->count();
            $chartData[] = $count;
        }

        // Data for Top 5 Apps (Downloads)
        $top5 = Analytic::where('event_type', 'download')
            ->select('mobile_app_id', DB::raw('count(*) as total'))
            ->groupBy('mobile_app_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
            
        $topAppsLabels = [];
        $topAppsData = [];
        foreach ($top5 as $stat) {
            $app = MobileApp::find($stat->mobile_app_id);
            if ($app) {
                $topAppsLabels[] = $app->app_name;
                $topAppsData[] = $stat->total;
            }
        }

        $recentEvents = Analytic::with('mobileApp')->latest()->limit(10)->get();

        return view('admin.analytics.index', compact(
            'totalDownloads', 'totalViews', 'topApp', 
            'chartLabels', 'chartData', 
            'topAppsLabels', 'topAppsData', 
            'recentEvents'
        ));
    }
}

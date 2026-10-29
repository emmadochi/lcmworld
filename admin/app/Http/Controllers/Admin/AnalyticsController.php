<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Analytic;
use App\Models\MobileApp;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $days = (int) $request->input('days', 7);
        if (!in_array($days, [7, 14, 30, 90])) {
            $days = 7;
        }

        $totalDownloads = Analytic::where('event_type', 'download')->count();
        $totalViews = Analytic::where('event_type', 'view')->count();
        $totalUsers = User::count();
        $newUsersInPeriod = User::where('created_at', '>=', Carbon::now()->subDays($days))->count();
        $conversionRate = ($totalViews > 0) ? round(($totalDownloads / $totalViews) * 100, 1) : 0;

        // Most downloaded app
        $topAppStats = Analytic::where('event_type', 'download')
            ->select('mobile_app_id', DB::raw('count(*) as total'))
            ->groupBy('mobile_app_id')
            ->orderByDesc('total')
            ->first();
            
        $topApp = $topAppStats ? MobileApp::find($topAppStats->mobile_app_id) : null;

        // Data for Downloads & Signups Over Time
        $chartLabels = [];
        $downloadsData = [];
        $signupsData = [];
        $viewsData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->format('M d');
            
            $dlCount = Analytic::where('event_type', 'download')
                ->whereDate('created_at', $date)
                ->count();
            $downloadsData[] = $dlCount;

            $vwCount = Analytic::where('event_type', 'view')
                ->whereDate('created_at', $date)
                ->count();
            $viewsData[] = $vwCount;

            $suCount = User::whereDate('created_at', $date)->count();
            $signupsData[] = $suCount;
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

        // Latest registered users
        $recentUsers = User::withCount('reviews')->latest()->limit(8)->get();

        // Recent events
        $recentEvents = Analytic::with('mobileApp')->latest()->limit(10)->get();

        return view('admin.analytics.index', compact(
            'totalDownloads', 'totalViews', 'totalUsers', 'newUsersInPeriod',
            'conversionRate', 'topApp', 'days',
            'chartLabels', 'downloadsData', 'signupsData', 'viewsData',
            'topAppsLabels', 'topAppsData',
            'recentUsers', 'recentEvents'
        ));
    }
}

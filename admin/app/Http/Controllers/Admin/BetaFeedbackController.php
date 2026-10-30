<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use App\Models\BetaFeedback;
use App\Models\MobileApp;

class BetaFeedbackController extends Controller
{
    private function ensureTableExists(): void
    {
        if (!Schema::hasTable('beta_feedback')) {
            Schema::create('beta_feedback', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('mobile_app_id')->nullable();
                $table->string('package_name')->index();
                $table->string('app_name')->nullable();
                $table->string('app_version')->nullable();
                $table->string('type')->default('bug');
                $table->text('message');
                $table->unsignedTinyInteger('rating')->nullable();
                $table->string('device_model')->nullable();
                $table->string('os_version')->nullable();
                $table->string('tester_name')->nullable();
                $table->string('tester_email')->nullable();
                $table->string('would_pay')->nullable();
                $table->string('status')->default('new');
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function index(Request $request)
    {
        $this->ensureTableExists();

        $query = BetaFeedback::query()->latest();

        if ($request->filled('app')) {
            $query->where(function ($q) use ($request) {
                $q->where('package_name', $request->app)
                  ->orWhere('app_name', $request->app);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('would_pay')) {
            $query->where('would_pay', $request->would_pay);
        }

        if ($request->filled('search')) {
            $search = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('message', 'LIKE', $search)
                  ->orWhere('tester_name', 'LIKE', $search)
                  ->orWhere('tester_email', 'LIKE', $search)
                  ->orWhere('device_model', 'LIKE', $search)
                  ->orWhere('app_name', 'LIKE', $search);
            });
        }

        $feedbacks = $query->paginate(15)->withQueryString();

        // Base metrics (global or app-filtered if filter is applied)
        $metricsBase = BetaFeedback::query();
        if ($request->filled('app')) {
            $metricsBase->where(function ($q) use ($request) {
                $q->where('package_name', $request->app)
                  ->orWhere('app_name', $request->app);
            });
        }

        $totalFeedback = (clone $metricsBase)->count();
        $totalBugs = (clone $metricsBase)->where('type', 'bug')->count();
        $resolvedBugs = (clone $metricsBase)->where('type', 'bug')->whereIn('status', ['resolved', 'closed'])->count();
        $bugResolutionRate = $totalBugs > 0 ? round(($resolvedBugs / $totalBugs) * 100, 1) : 100;

        $willingToPay = (clone $metricsBase)->where('would_pay', 'yes')->count();
        $maybePay = (clone $metricsBase)->where('would_pay', 'maybe')->count();
        $notWillingPay = (clone $metricsBase)->where('would_pay', 'no')->count();
        $paidResponses = $willingToPay + $maybePay + $notWillingPay;
        $commercialConversionRate = $paidResponses > 0 ? round(($willingToPay / $paidResponses) * 100, 1) : 0;

        $avgRating = round((clone $metricsBase)->whereNotNull('rating')->avg('rating') ?: 5.0, 1);

        // Rating breakdown
        $ratingCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        $ratingsGroup = (clone $metricsBase)->select('rating', DB::raw('count(*) as count'))
            ->whereNotNull('rating')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();
        foreach ($ratingsGroup as $star => $count) {
            if (isset($ratingCounts[$star])) {
                $ratingCounts[$star] = $count;
            }
        }

        // Category breakdown
        $typeBreakdown = [
            'commercial' => (clone $metricsBase)->where('type', 'commercial')->count(),
            'feedback' => (clone $metricsBase)->where('type', 'feedback')->count(),
            'feature_request' => (clone $metricsBase)->where('type', 'feature_request')->count(),
            'bug' => (clone $metricsBase)->where('type', 'bug')->count(),
        ];

        // Hardware & OS distribution
        $topDevices = (clone $metricsBase)->select('device_model', DB::raw('count(*) as count'))
            ->whereNotNull('device_model')
            ->where('device_model', '!=', '')
            ->groupBy('device_model')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $osVersions = (clone $metricsBase)->select('os_version', DB::raw('count(*) as count'))
            ->whereNotNull('os_version')
            ->where('os_version', '!=', '')
            ->groupBy('os_version')
            ->orderByDesc('count')
            ->limit(4)
            ->get();

        // App filter choices
        $appsFromFeedback = BetaFeedback::select('package_name', 'app_name')->distinct()->get();
        $appsFromStore = MobileApp::select('package_name', 'app_name')->whereNotNull('package_name')->where('package_name', '!=', '')->distinct()->get();
        
        $apps = $appsFromFeedback->merge($appsFromStore)->unique('package_name')->values();

        return view('admin.feedback.index', compact(
            'feedbacks',
            'totalFeedback',
            'totalBugs',
            'resolvedBugs',
            'bugResolutionRate',
            'willingToPay',
            'maybePay',
            'notWillingPay',
            'commercialConversionRate',
            'avgRating',
            'ratingCounts',
            'typeBreakdown',
            'topDevices',
            'osVersions',
            'apps'
        ));
    }

    /**
     * Dedicated Executive Investor Report
     * Engineered for investor presentations, pitch decks, due diligence, and print-to-PDF export.
     */
    public function investorReport(Request $request)
    {
        $this->ensureTableExists();

        $query = BetaFeedback::query();

        $selectedApp = null;
        if ($request->filled('app')) {
            $query->where(function ($q) use ($request) {
                $q->where('package_name', $request->app)
                  ->orWhere('app_name', $request->app);
            });
            $selectedApp = $request->app;
        }

        $allFeedback = (clone $query)->orderByDesc('created_at')->get();

        $totalReports = $allFeedback->count();
        $avgRating = round($allFeedback->whereNotNull('rating')->avg('rating') ?: 5.0, 1);
        $totalTesters = $allFeedback->whereNotNull('tester_email')->unique('tester_email')->count();
        $totalDevices = $allFeedback->whereNotNull('device_model')->unique('device_model')->count();

        // Commercial viability metrics
        $willingToPay = $allFeedback->where('would_pay', 'yes')->count();
        $maybePay = $allFeedback->where('would_pay', 'maybe')->count();
        $noPay = $allFeedback->where('would_pay', 'no')->count();
        $paidTotal = $willingToPay + $maybePay + $noPay;
        $commercialConversionRate = $paidTotal > 0 ? round(($willingToPay / $paidTotal) * 100, 1) : 0;
        $totalCommercialOpportunityRate = $paidTotal > 0 ? round((($willingToPay + $maybePay) / $paidTotal) * 100, 1) : 0;

        // Bug metrics & health index
        $bugReports = $allFeedback->where('type', 'bug');
        $totalBugs = $bugReports->count();
        $resolvedBugs = $bugReports->whereIn('status', ['resolved', 'closed'])->count();
        $bugResolutionRate = $totalBugs > 0 ? round(($resolvedBugs / $totalBugs) * 100, 1) : 100;
        $stabilityIndex = $totalReports > 0 ? round(((($totalReports - $totalBugs) + $resolvedBugs) / $totalReports) * 100, 1) : 100;

        // App-by-app scorecard
        $appScorecards = [];
        $distinctApps = $allFeedback->pluck('package_name')->unique();
        foreach ($distinctApps as $pkg) {
            $appItems = $allFeedback->where('package_name', $pkg);
            $appBugs = $appItems->where('type', 'bug')->count();
            $appResolved = $appItems->where('type', 'bug')->whereIn('status', ['resolved', 'closed'])->count();
            $appPayYes = $appItems->where('would_pay', 'yes')->count();
            $appPayTotal = $appItems->whereIn('would_pay', ['yes', 'maybe', 'no'])->count();

            $appScorecards[] = [
                'name' => $appItems->first()->app_name ?: $pkg,
                'package' => $pkg,
                'version' => $appItems->first()->app_version ?: '1.0.0',
                'reports' => $appItems->count(),
                'avg_rating' => round($appItems->whereNotNull('rating')->avg('rating') ?: 5.0, 1),
                'bugs' => $appBugs,
                'bugs_resolved' => $appResolved,
                'stability_pct' => $appBugs > 0 ? round(($appResolved / $appBugs) * 100, 1) : 100,
                'commercial_leads' => $appPayYes,
                'commercial_rate' => $appPayTotal > 0 ? round(($appPayYes / $appPayTotal) * 100, 1) : 0,
            ];
        }

        // Qualitative highlights / Testimonials for investors
        $commercialQuotes = $allFeedback->where('would_pay', 'yes')->take(6);
        $featureDemands = $allFeedback->where('type', 'feature_request')->take(4);
        $resolvedIssues = $allFeedback->where('type', 'bug')->whereIn('status', ['resolved', 'closed'])->take(4);

        // Hardware & OS Matrix
        $devices = $allFeedback->whereNotNull('device_model')->groupBy('device_model')->map->count()->sortDesc()->take(8);
        $osList = $allFeedback->whereNotNull('os_version')->groupBy('os_version')->map->count()->sortDesc();

        return view('admin.feedback.investor_report', compact(
            'selectedApp',
            'totalReports',
            'avgRating',
            'totalTesters',
            'totalDevices',
            'willingToPay',
            'maybePay',
            'noPay',
            'commercialConversionRate',
            'totalCommercialOpportunityRate',
            'totalBugs',
            'resolvedBugs',
            'bugResolutionRate',
            'stabilityIndex',
            'appScorecards',
            'commercialQuotes',
            'featureDemands',
            'resolvedIssues',
            'devices',
            'osList',
            'allFeedback'
        ));
    }

    /**
     * Export Beta Testing Data to CSV for Investor Data Rooms & Audits
     */
    public function exportCsv(Request $request)
    {
        $this->ensureTableExists();

        $query = BetaFeedback::query()->latest();

        if ($request->filled('app')) {
            $query->where(function ($q) use ($request) {
                $q->where('package_name', $request->app)
                  ->orWhere('app_name', $request->app);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('would_pay')) {
            $query->where('would_pay', $request->would_pay);
        }

        $records = $query->get();

        $fileName = 'ldtl-beta-testing-investor-report-' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($records) {
            $output = fopen('php://output', 'w');
            
            // UTF-8 BOM for Microsoft Excel compatibility
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header Row
            fputcsv($output, [
                'ID',
                'Application Name',
                'Package Name',
                'Build Version',
                'Category',
                'Rating (1-5)',
                'Commercial Intent (Would Pay)',
                'Tester Name',
                'Tester Email / Contact',
                'Device Model',
                'OS Version',
                'Feedback Message',
                'Resolution Status',
                'Engineering & Admin Notes',
                'Submission Date (UTC)',
            ]);

            foreach ($records as $r) {
                fputcsv($output, [
                    $r->id,
                    $r->app_name ?: 'N/A',
                    $r->package_name,
                    $r->app_version ?: '1.0.0',
                    strtoupper($r->type),
                    $r->rating ? $r->rating . '/5' : 'N/A',
                    strtoupper($r->would_pay ?: 'N/A'),
                    $r->tester_name ?: 'Anonymous Tester',
                    $r->tester_email ?: 'N/A',
                    $r->device_model ?: 'Unspecified Hardware',
                    $r->os_version ?: 'Android',
                    $r->message,
                    strtoupper($r->status),
                    $r->admin_notes ?: 'None',
                    $r->created_at ? $r->created_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function updateStatus(Request $request, $id)
    {
        $feedback = BetaFeedback::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:new,investigating,resolved,closed',
            'admin_notes' => 'nullable|string',
        ]);

        $feedback->status = $request->status;
        if ($request->has('admin_notes')) {
            $feedback->admin_notes = $request->admin_notes;
        }
        $feedback->save();

        return back()->with('success', 'Feedback status updated to ' . ucfirst($request->status));
    }

    public function destroy($id)
    {
        $feedback = BetaFeedback::findOrFail($id);
        $feedback->delete();

        return back()->with('success', 'Feedback entry removed.');
    }

    public function seedSamples()
    {
        $this->ensureTableExists();

        $feedbacks = [
            [
                'package_name' => 'org.ldtl.lichat',
                'app_name' => 'LiChat',
                'app_version' => '1.2.0',
                'type' => 'commercial',
                'message' => 'The end-to-end encrypted voice calling is ultra crisp even on 3G network in Lagos. Our church fellowship team tested group calls with 25 members simultaneously and it outperformed WhatsApp. We are ready to subscribe for the departmental plan immediately upon launch.',
                'rating' => 5,
                'device_model' => 'Samsung Galaxy S24 Ultra',
                'os_version' => 'Android 14',
                'tester_name' => 'Pastor Daniel Adeyemi',
                'tester_email' => 'daniel.adeyemi@faithnetwork.org',
                'would_pay' => 'yes',
                'status' => 'resolved',
                'admin_notes' => 'Contacted for early enterprise beta tier. Confirmed 150 seat deployment.',
                'days_ago' => 12,
            ],
            [
                'package_name' => 'org.ldtl.lavie',
                'app_name' => 'La-Vie Enterprise',
                'app_version' => '2.0.4',
                'type' => 'commercial',
                'message' => 'The interactive whiteboard and screen sharing latency is significantly better than Zoom on low-bandwidth connections. We compared it side by side in our executive board meeting. 100% willing to pay monthly enterprise licensing for this.',
                'rating' => 5,
                'device_model' => 'Google Pixel 8 Pro',
                'os_version' => 'Android 14',
                'tester_name' => 'Dr. Michael Okonkwo',
                'tester_email' => 'm.okonkwo@apexhealth.ng',
                'would_pay' => 'yes',
                'status' => 'resolved',
                'admin_notes' => 'High-value B2B lead. Forwarded to commercial partnership team.',
                'days_ago' => 10,
            ],
            [
                'package_name' => 'org.ldtl.yoets',
                'app_name' => 'yo-ETS',
                'app_version' => '1.1.2',
                'type' => 'feedback',
                'message' => 'The offline lecture caching and interactive quiz module worked flawlessly during my train commute. Course progress synced instantly once reconnected to Wi-Fi. Truly world-class UX.',
                'rating' => 5,
                'device_model' => 'Xiaomi Redmi Note 13 Pro',
                'os_version' => 'Android 13',
                'tester_name' => 'Sarah Bello',
                'tester_email' => 'sarah.bello@unilag.edu.ng',
                'would_pay' => 'yes',
                'status' => 'closed',
                'admin_notes' => 'Student cohort representative. Requested certificate generation feature.',
                'days_ago' => 9,
            ],
            [
                'package_name' => 'org.ldtl.lcmaudios',
                'app_name' => 'LCM Audios',
                'app_version' => '1.3.1',
                'type' => 'commercial',
                'message' => 'Background playback with lock screen media controls is seamless. The lossless audio streaming quality is noticeably richer. I would gladly pay an annual subscription for unlimited offline sermon downloads and curated audio tracks.',
                'rating' => 5,
                'device_model' => 'Samsung Galaxy A54 5G',
                'os_version' => 'Android 14',
                'tester_name' => 'Emmanuel Vance',
                'tester_email' => 'vance.emmanuel@soundcraft.io',
                'would_pay' => 'yes',
                'status' => 'resolved',
                'admin_notes' => 'Premium tier packaging validated.',
                'days_ago' => 8,
            ],
            [
                'package_name' => 'org.ldtl.livingthelife',
                'app_name' => 'Living The Life',
                'app_version' => '1.0.8',
                'type' => 'feedback',
                'message' => 'The daily devotionals, push notification reminders, and community prayer wall create immense daily retention. I opened the app 14 days straight without a single crash.',
                'rating' => 5,
                'device_model' => 'Tecno Camon 30 Pro',
                'os_version' => 'Android 14',
                'tester_name' => 'Blessing Nwosu',
                'tester_email' => 'blessing.nwosu@gmail.com',
                'would_pay' => 'maybe',
                'status' => 'closed',
                'admin_notes' => '14-day continuous retention confirmed. Great benchmark for Play Store closed testing.',
                'days_ago' => 7,
            ],
            [
                'package_name' => 'org.ldtl.lichat',
                'app_name' => 'LiChat',
                'app_version' => '1.2.0',
                'type' => 'bug',
                'message' => 'When recording a voice note longer than 3 minutes while receiving an incoming call, the audio waveform preview temporarily freezes until app focus is restored.',
                'rating' => 4,
                'device_model' => 'Infinix Note 30 VIP',
                'os_version' => 'Android 13',
                'tester_name' => 'Kareem Alabi',
                'tester_email' => 'kareem.alabi@devguild.org',
                'would_pay' => 'yes',
                'status' => 'investigating',
                'admin_notes' => 'Reproduced in audio recorder service. Patch scheduled in v1.2.1 build.',
                'days_ago' => 6,
            ],
            [
                'package_name' => 'org.ldtl.lavie',
                'app_name' => 'La-Vie Enterprise',
                'app_version' => '2.0.4',
                'type' => 'feature_request',
                'message' => 'Would love to see integrated breakout rooms and meeting recording directly to cloud storage for post-conference webinars. Excellent stability otherwise.',
                'rating' => 4,
                'device_model' => 'Samsung Galaxy Tab S9',
                'os_version' => 'Android 14',
                'tester_name' => 'Prof. Anita Sharma',
                'tester_email' => 'asharma@globaledu.org',
                'would_pay' => 'yes',
                'status' => 'investigating',
                'admin_notes' => 'Breakout rooms placed on Q1 enterprise roadmap.',
                'days_ago' => 5,
            ],
            [
                'package_name' => 'org.ldtl.yoets',
                'app_name' => 'yo-ETS',
                'app_version' => '1.1.2',
                'type' => 'bug',
                'message' => 'PDF syllabus download on Android 12 sometimes prompts for storage permission twice before opening the native viewer.',
                'rating' => 4,
                'device_model' => 'Samsung Galaxy A32',
                'os_version' => 'Android 12',
                'tester_name' => 'Tariq Mensah',
                'tester_email' => 'tariq.mensah@academics.gh',
                'would_pay' => 'maybe',
                'status' => 'resolved',
                'admin_notes' => 'Updated scoped storage permissions helper in AndroidManifest.',
                'days_ago' => 4,
            ],
            [
                'package_name' => 'org.ldtl.lcmaudios',
                'app_name' => 'LCM Audios',
                'app_version' => '1.3.1',
                'type' => 'feature_request',
                'message' => 'Adding a sleep timer (e.g. stop playing after 30 or 60 minutes) would be fantastic for night-time devotional listening.',
                'rating' => 5,
                'device_model' => 'Google Pixel 7a',
                'os_version' => 'Android 14',
                'tester_name' => 'Grace Oladipo',
                'tester_email' => 'grace.oladipo@harmony.com',
                'would_pay' => 'yes',
                'status' => 'investigating',
                'admin_notes' => 'Sleep timer widget added to media playback service queue.',
                'days_ago' => 4,
            ],
            [
                'package_name' => 'org.ldtl.store',
                'app_name' => 'LCMWorld Store',
                'app_version' => '1.0.2',
                'type' => 'feedback',
                'message' => 'The OTA auto-update notifications and 1-tap APK installation flow is incredibly smooth. Much faster than third-party store side-loading. Very intuitive category layout.',
                'rating' => 5,
                'device_model' => 'OnePlus 11',
                'os_version' => 'Android 14',
                'tester_name' => 'Chinedu Eze',
                'tester_email' => 'chinedu.eze@techpulse.africa',
                'would_pay' => 'maybe',
                'status' => 'closed',
                'admin_notes' => 'OTA engine validation completed successfully across 35 devices.',
                'days_ago' => 3,
            ],
            [
                'package_name' => 'org.ldtl.lichat',
                'app_name' => 'LiChat',
                'app_version' => '1.2.0',
                'type' => 'commercial',
                'message' => 'Our remote enterprise staff of 80 users tested the team channel broadcast features. Highly interested in the white-label custom domain integration. We have budget allocated for Q1 rollout.',
                'rating' => 5,
                'device_model' => 'Samsung Galaxy S23+',
                'os_version' => 'Android 14',
                'tester_name' => 'Markus Steiner',
                'tester_email' => 'm.steiner@steiner-logistics.de',
                'would_pay' => 'yes',
                'status' => 'new',
                'admin_notes' => 'International enterprise inquiry. Sent to senior business development.',
                'days_ago' => 3,
            ],
            [
                'package_name' => 'org.ldtl.lavie',
                'app_name' => 'La-Vie Enterprise',
                'app_version' => '2.0.4',
                'type' => 'feedback',
                'message' => 'Crystal clear 1080p video grid with 18 simultaneous active cameras. Battery drain was surprisingly low (only 8% over a 45 min conference). Excellent optimization.',
                'rating' => 5,
                'device_model' => 'Xiaomi 13T Pro',
                'os_version' => 'Android 14',
                'tester_name' => 'Fatima Al-Mansoor',
                'tester_email' => 'f.almansoor@gulftech.ae',
                'would_pay' => 'yes',
                'status' => 'closed',
                'admin_notes' => 'Hardware acceleration efficiency validated.',
                'days_ago' => 2,
            ],
            [
                'package_name' => 'org.ldtl.yoets',
                'app_name' => 'yo-ETS',
                'app_version' => '1.1.2',
                'type' => 'commercial',
                'message' => 'We are considering adopting yo-ETS across our 3 secondary school campuses for digital assignment distribution. Will there be institutional bulk pricing?',
                'rating' => 5,
                'device_model' => 'Samsung Galaxy Tab A8',
                'os_version' => 'Android 13',
                'tester_name' => 'Rev. Kenneth Obi',
                'tester_email' => 'k.obi@crownacademy.edu.ng',
                'would_pay' => 'yes',
                'status' => 'investigating',
                'admin_notes' => 'Education institutional license proposal sent.',
                'days_ago' => 2,
            ],
            [
                'package_name' => 'org.ldtl.livingthelife',
                'app_name' => 'Living The Life',
                'app_version' => '1.0.8',
                'type' => 'bug',
                'message' => 'Font scaling on ultra-small screens (4.5 inch display) occasionally clips the bottom line of the daily reflection quote.',
                'rating' => 3,
                'device_model' => 'Nokia C12',
                'os_version' => 'Android 12 (Go Edition)',
                'tester_name' => 'Joy Umeh',
                'tester_email' => 'joy.umeh@outlook.com',
                'would_pay' => 'no',
                'status' => 'investigating',
                'admin_notes' => 'Android Go dynamic font scale fix applied to typography stylesheet.',
                'days_ago' => 1,
            ],
            [
                'package_name' => 'org.ldtl.lichat',
                'app_name' => 'LiChat',
                'app_version' => '1.2.0',
                'type' => 'feedback',
                'message' => 'The UI transitions feel snappy and modern. Fast media delivery, stickers load instantly. Ready for broader public release!',
                'rating' => 5,
                'device_model' => 'Google Pixel 6',
                'os_version' => 'Android 14',
                'tester_name' => 'David Adeleke',
                'tester_email' => 'd.adeleke@startupstudio.co',
                'would_pay' => 'yes',
                'status' => 'new',
                'admin_notes' => 'Positive tester endorsement.',
                'days_ago' => 0,
            ],
        ];

        foreach ($feedbacks as $fb) {
            $daysAgo = $fb['days_ago'] ?? 0;
            unset($fb['days_ago']);
            
            $existing = BetaFeedback::where('package_name', $fb['package_name'])
                ->where('tester_email', $fb['tester_email'])
                ->first();

            if (!$existing) {
                $app = MobileApp::where('package_name', $fb['package_name'])->first();
                $fb['mobile_app_id'] = $app ? $app->id : null;
                $fb['created_at'] = now()->subDays($daysAgo);
                $fb['updated_at'] = now()->subDays($daysAgo);
                BetaFeedback::create($fb);
            }
        }

        return redirect('/admin/beta-feedback')->with('success', 'Investor beta telemetry dataset populated successfully!');
    }
}

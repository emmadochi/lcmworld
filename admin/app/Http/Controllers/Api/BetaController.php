<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\MobileApp;
use App\Models\BetaFeedback;

class BetaController extends Controller
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

    /**
     * Check if a newer version of the app is available on LCMWorld.
     */
    public function checkUpdate(Request $request)
    {
        $package = $request->query('package');
        $appName = $request->query('app_name');
        $currentVersion = $request->query('version', '1.0.0');

        $query = MobileApp::query();
        if ($package) {
            $query->where('package_name', $package);
        } elseif ($appName) {
            $query->where('app_name', 'LIKE', '%' . $appName . '%');
        } else {
            return response()->json(['error' => 'Please provide a package or app_name parameter.'], 400);
        }

        $app = $query->first();

        if (!$app) {
            return response()->json([
                'found' => false,
                'has_update' => false,
                'message' => 'App not registered on LCMWorld store.',
            ], 404);
        }

        $latestVersion = $app->version ?? '1.0.0';
        $hasUpdate = version_compare($latestVersion, $currentVersion, '>');

        return response()->json([
            'found' => true,
            'has_update' => $hasUpdate,
            'app_id' => $app->id,
            'app_name' => $app->app_name,
            'package_name' => $app->package_name,
            'current_version' => $currentVersion,
            'latest_version' => $latestVersion,
            'whats_new' => $app->whats_new ?: 'Bug fixes, performance improvements, and optimizations.',
            'file_size' => $app->file_size,
            'download_url' => url("/api/admin/apps/{$app->id}/download"),
            'icon_url' => $app->icon_url,
        ]);
    }

    /**
     * Submit beta feedback, bug report, or UX review from any client app.
     */
    public function submitFeedback(Request $request)
    {
        $this->ensureTableExists();

        $validated = $request->validate([
            'package_name' => 'required|string',
            'app_name'     => 'nullable|string',
            'app_version'  => 'nullable|string',
            'type'         => 'nullable|string', // bug, feedback, feature_request, commercial
            'message'      => 'required|string',
            'rating'       => 'nullable|integer|min:1|max:5',
            'device_model' => 'nullable|string',
            'os_version'   => 'nullable|string',
            'tester_name'  => 'nullable|string',
            'tester_email' => 'nullable|string',
            'would_pay'    => 'nullable|string',
        ]);

        // Attempt to associate with an app in the store
        $app = MobileApp::where('package_name', $validated['package_name'])
            ->orWhere('app_name', $validated['app_name'] ?? '')
            ->first();

        $feedback = new BetaFeedback();
        $feedback->mobile_app_id = $app ? $app->id : null;
        $feedback->package_name  = $validated['package_name'];
        $feedback->app_name      = $validated['app_name'] ?? ($app ? $app->app_name : $validated['package_name']);
        $feedback->app_version   = $validated['app_version'] ?? ($app ? $app->version : '1.0.0');
        $feedback->type          = $validated['type'] ?? 'bug';
        $feedback->message       = $validated['message'];
        $feedback->rating        = $validated['rating'] ?? null;
        $feedback->device_model  = $validated['device_model'] ?? null;
        $feedback->os_version    = $validated['os_version'] ?? null;
        $feedback->tester_name   = $validated['tester_name'] ?? null;
        $feedback->tester_email  = $validated['tester_email'] ?? null;
        $feedback->would_pay     = $validated['would_pay'] ?? null;
        $feedback->status        = 'new';
        $feedback->save();

        return response()->json([
            'success' => true,
            'message' => 'Beta feedback submitted successfully. Thank you for your contribution!',
            'feedback_id' => $feedback->id,
        ], 201);
    }
}

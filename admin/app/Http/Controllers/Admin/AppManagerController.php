<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MobileApp;
use App\Models\Category;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Storage;

class AppManagerController extends Controller
{
    public function index()
    {
        $apps = MobileApp::with('category')->latest()->get();
        $categories = Category::all();
        
        return view('admin.apps.index', compact('apps', 'categories'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'package_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'version' => 'required|string|max:50',
            'whats_new' => 'nullable|string',
            'apk' => 'required|file',
            'icon' => 'nullable|image|max:2048',
            'screenshots.*' => 'nullable|image|max:3072',
        ]);

        $app = new MobileApp();
        $app->app_name = $request->app_name;
        $app->package_name = $request->package_name;
        $app->description = $request->description;
        $app->category_id = $request->category_id;
        $app->version = $request->version;
        $app->whats_new = $request->whats_new;
        $app->rating = 5.0;
        $app->review_count = '0';

        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('icons', 'public');
            $app->icon_url = $iconPath;
        }

        if ($request->hasFile('screenshots')) {
            $screenshotPaths = [];
            foreach ($request->file('screenshots') as $screenshot) {
                if ($screenshot->isValid()) {
                    $screenshotPaths[] = $screenshot->store('screenshots', 'public');
                }
            }
            $app->screenshots = $screenshotPaths;
        }

        if ($request->hasFile('apk')) {
            $apkFile = $request->file('apk');
            $apkPath = $apkFile->store('apks', 'public');
            $app->apk_download_url = $apkPath;

            $bytes = $apkFile->getSize();
            if ($bytes >= 1048576) {
                $app->file_size = round($bytes / 1048576, 1) . ' MB';
            } else {
                $app->file_size = round($bytes / 1024, 0) . ' KB';
            }
        }

        $app->save();

        // Send Push Notification
        try {
            $firebase = new FirebaseService();
            $firebase->sendNotification(
                'all_users',
                'New App: ' . $app->app_name,
                "v{$app->version} is now available in LCM World.",
                [
                    'app_id' => (string) $app->id,
                    'action' => 'open_app',
                ]
            );
        } catch (\Exception $e) {
            \Log::warning('Firebase notification dispatch failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'App uploaded successfully',
            'app_id' => $app->id,
        ]);
    }

    public function destroy($id)
    {
        $app = MobileApp::findOrFail($id);

        // Delete associated files from storage
        $rawIcon = $app->getRawOriginal('icon_url');
        if ($rawIcon && Storage::disk('public')->exists($rawIcon)) {
            Storage::disk('public')->delete($rawIcon);
        }

        $rawApk = $app->getRawOriginal('apk_download_url');
        if ($rawApk && Storage::disk('public')->exists($rawApk)) {
            Storage::disk('public')->delete($rawApk);
        }

        $rawScreenshots = $app->getRawOriginal('screenshots');
        if (!empty($rawScreenshots)) {
            $screenshots = is_array($rawScreenshots) ? $rawScreenshots : json_decode($rawScreenshots, true);
            if (is_array($screenshots)) {
                foreach ($screenshots as $shot) {
                    if (Storage::disk('public')->exists($shot)) {
                        Storage::disk('public')->delete($shot);
                    }
                }
            }
        }

        $app->delete();

        return redirect()->back()->with('success', 'App deleted successfully.');
    }

    public function toggleFeatured($id)
    {
        $app = MobileApp::findOrFail($id);
        $app->is_featured = !$app->is_featured;
        $app->save();

        $status = $app->is_featured ? 'featured' : 'unfeatured';
        return redirect()->back()->with('success', "App \"{$app->app_name}\" is now {$status}.");
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'package_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'version' => 'required|string|max:50',
            'whats_new' => 'nullable|string',
            'icon' => 'nullable|image|max:2048',
            'apk' => 'nullable|file',
        ]);

        $app = MobileApp::findOrFail($id);
        $app->app_name = $request->app_name;
        if ($request->filled('package_name')) {
            $app->package_name = $request->package_name;
        }
        $app->description = $request->description;
        $app->category_id = $request->category_id;
        $app->version = $request->version;
        $app->whats_new = $request->whats_new;

        if ($request->hasFile('icon')) {
            $oldIcon = $app->getRawOriginal('icon_url');
            if ($oldIcon && Storage::disk('public')->exists($oldIcon)) {
                Storage::disk('public')->delete($oldIcon);
            }
            $app->icon_url = $request->file('icon')->store('icons', 'public');
        }

        if ($request->hasFile('apk')) {
            $oldApk = $app->getRawOriginal('apk_download_url');
            if ($oldApk && Storage::disk('public')->exists($oldApk)) {
                Storage::disk('public')->delete($oldApk);
            }
            $apkFile = $request->file('apk');
            $app->apk_download_url = $apkFile->store('apks', 'public');

            $bytes = $apkFile->getSize();
            if ($bytes >= 1048576) {
                $app->file_size = round($bytes / 1048576, 1) . ' MB';
            } else {
                $app->file_size = round($bytes / 1024, 0) . ' KB';
            }
        }

        $app->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'App updated successfully',
            ]);
        }

        return redirect()->back()->with('success', "App \"{$app->app_name}\" updated successfully.");
    }
}

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
            'apk' => 'required|file', // Ideally we add mimes:apk but it sometimes fails on different systems
            'icon' => 'nullable|image|max:2048',
        ]);

        $app = new MobileApp();
        $app->app_name = $request->app_name;
        $app->package_name = $request->package_name;
        $app->description = $request->description;
        $app->category_id = $request->category_id;
        $app->version = $request->version;
        $app->rating = 5.0;
        $app->review_count = 0;

        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('icons', 'public');
            $app->icon_url = $iconPath;
        }

        if ($request->hasFile('apk')) {
            // Store APK file publicly accessible
            $apkPath = $request->file('apk')->store('apks', 'public');
            // Full URL is required for the flutter app to download it
            $app->apk_download_url = url('storage/' . $apkPath);
        }

        $app->save();

        // Send Push Notification
        $firebase = new FirebaseService();
        $firebase->sendNotification(
            'all_users',
            'New App Available!',
            "{$app->app_name} is now available in the catalog.",
            [
                'app_id' => (string) $app->id,
                'action' => 'open_app'
            ]
        );

        return response()->json(['success' => true, 'message' => 'App uploaded successfully']);
    }
}

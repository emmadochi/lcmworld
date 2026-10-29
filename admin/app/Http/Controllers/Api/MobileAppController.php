<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MobileApp;
use App\Models\Category;

class MobileAppController extends Controller
{
    public function index()
    {
        $apps = MobileApp::with('category')->latest()->get();
        return response()->json($apps);
    }

    public function featured()
    {
        $apps = MobileApp::with('category')->where('is_featured', true)->latest()->get();
        return response()->json($apps);
    }

    public function categories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function download($id)
    {
        // Prevent timeout during large multi-megabyte app downloads
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', '1');
        }

        $app = MobileApp::findOrFail($id);
        $rawPath = $app->getRawOriginal('apk_download_url');

        // External direct link (e.g. speed test CDN)
        if (filter_var($rawPath, FILTER_VALIDATE_URL) && !str_contains($rawPath, 'localhost') && !str_contains($rawPath, '127.0.0.1') && !str_contains($rawPath, 'lifechangerstouch.org')) {
            return redirect()->away($rawPath);
        }

        // Clean relative path
        if (filter_var($rawPath, FILTER_VALIDATE_URL)) {
            $parsed = parse_url($rawPath);
            if (isset($parsed['path'])) {
                $pos = strpos($parsed['path'], '/storage/');
                if ($pos !== false) {
                    $rawPath = substr($parsed['path'], $pos + 9);
                }
            }
        }

        $rawPath = ltrim($rawPath, '/');
        $filePath = storage_path('app/public/' . $rawPath);
        if (!file_exists($filePath)) {
            $filePath = public_path('storage/' . $rawPath);
        }

        if (!file_exists($filePath) || is_dir($filePath)) {
            return response()->json([
                'error' => 'APK package file not found on server.',
                'path' => $rawPath,
            ], 404);
        }

        // Automatically track download
        try {
            $analytic = new \App\Models\Analytic();
            $analytic->mobile_app_id = $app->id;
            $analytic->event_type = 'download';
            $analytic->ip_address = request()->ip();
            $analytic->save();
        } catch (\Exception $e) {}

        $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $app->app_name) . '.apk';

        return response()->file($filePath, [
            'Content-Type' => 'application/vnd.android.package-archive',
            'Content-Disposition' => 'attachment; filename="' . $safeName . '"',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, must-revalidate, max-age=0',
            'Pragma' => 'public',
        ]);
    }

    public function media($path)
    {
        $cleanPath = ltrim($path, '/');
        $filePath = storage_path('app/public/' . $cleanPath);
        if (!file_exists($filePath)) {
            $filePath = public_path('storage/' . $cleanPath);
        }

        if (!file_exists($filePath) || is_dir($filePath)) {
            abort(404, 'Media not found');
        }

        return response()->file($filePath, [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function track(Request $request, $id)
    {
        $app = MobileApp::findOrFail($id);
        
        $type = $request->input('type', 'view'); // 'view' or 'download'
        
        $analytic = new \App\Models\Analytic();
        $analytic->mobile_app_id = $app->id;
        $analytic->event_type = $type;
        $analytic->ip_address = $request->ip();
        $analytic->save();

        return response()->json(['success' => true]);
    }
}

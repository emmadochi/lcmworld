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
        $apps = MobileApp::with('category')->get();
        return response()->json($apps);
    }

    public function featured()
    {
        $apps = MobileApp::with('category')->where('is_featured', true)->get();
        return response()->json($apps);
    }

    public function categories()
    {
        $categories = Category::all();
        return response()->json($categories);
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

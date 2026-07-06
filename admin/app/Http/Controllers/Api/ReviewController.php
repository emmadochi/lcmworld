<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\MobileApp;

class ReviewController extends Controller
{
    public function index($appId)
    {
        $reviews = Review::with('user:id,name')
            ->where('mobile_app_id', $appId)
            ->latest()
            ->get();

        return response()->json($reviews);
    }

    public function store(Request $request, $appId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $app = MobileApp::findOrFail($appId);

        $review = Review::create([
            'mobile_app_id' => $app->id,
            'user_id' => $request->user()->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json($review, 201);
    }
}

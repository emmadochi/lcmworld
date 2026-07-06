<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Get all documents
     */
    public function index()
    {
        $documents = Document::latest()->get()->map(function ($doc) {
            return [
                'id' => $doc->id,
                'title' => $doc->title,
                'file_name' => $doc->file_name,
                // Make the file_path an absolute URL so the mobile app can download it directly
                'file_url' => asset('storage/' . $doc->file_path),
                'file_type' => $doc->file_type,
                'created_at' => $doc->created_at->toIso8601String(),
            ];
        });

        return response()->json($documents);
    }
}

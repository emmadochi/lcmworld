<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::latest()->get();
        return view('admin.documents.index', compact('documents'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'documents' => 'required|array',
            'documents.*' => 'required|file',
        ]);

        $uploadedDocs = [];

        foreach ($request->file('documents') as $file) {
            $fileName = $file->getClientOriginalName();
            $path = $file->store('documents', 'public');

            $doc = Document::create([
                'title' => pathinfo($fileName, PATHINFO_FILENAME),
                'file_name' => $fileName,
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
            ]);

            $uploadedDocs[] = $doc;
        }

        return response()->json(['success' => true, 'message' => count($uploadedDocs) . ' documents uploaded successfully']);
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        
        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category; // Assuming Category model exists
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = DB::table('categories')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon_url' => 'nullable|url'
        ]);

        DB::table('categories')->insert([
            'name' => $request->name,
            'icon_url' => $request->icon_url ?? '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/categories')->with('success', 'Category created successfully');
    }

    public function destroy($id)
    {
        DB::table('categories')->where('id', $id)->delete();
        return redirect('/admin/categories')->with('success', 'Category deleted successfully');
    }
}

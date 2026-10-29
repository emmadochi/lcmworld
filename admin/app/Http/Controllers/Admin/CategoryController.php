<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('mobileApps')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon_url' => 'nullable|url'
        ]);

        Category::create([
            'name' => $request->name,
            'icon_url' => $request->icon_url ?? '',
        ]);

        return redirect('/admin/categories')->with('success', 'Category created successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon_url' => 'nullable|url'
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'icon_url' => $request->icon_url ?? '',
        ]);

        return redirect('/admin/categories')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Unlink apps before deleting
        \App\Models\MobileApp::where('category_id', $id)->update(['category_id' => null]);
        
        $category->delete();
        return redirect('/admin/categories')->with('success', 'Category deleted successfully');
    }
}

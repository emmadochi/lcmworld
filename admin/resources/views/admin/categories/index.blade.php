@extends('layouts.admin')

@section('title', 'Categories Manager')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-white">Categories</h2>
        <p class="text-gray-400 mt-1">Manage app categories</p>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-lg bg-green-500/20 border border-green-500/50 text-green-400">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Categories List -->
    <div class="lg:col-span-2 glass-panel rounded-2xl border border-gray-800 p-6">
        <h3 class="text-xl font-semibold text-white mb-6">Existing Categories</h3>
        
        <div class="space-y-4">
            @forelse($categories as $category)
                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-800/50 border border-gray-700">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400">
                            @if($category->icon_url)
                                <img src="{{ $category->icon_url }}" alt="{{ $category->name }}" class="w-6 h-6 object-cover rounded">
                            @else
                                <i class="fa-solid fa-tag"></i>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-white font-medium">{{ $category->name }}</h4>
                            <p class="text-sm text-gray-500">ID: {{ $category->id }}</p>
                        </div>
                    </div>
                    <form action="/admin/categories/{{ $category->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-8 h-8 rounded-full bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors">
                            <i class="fa-solid fa-trash-alt text-sm"></i>
                        </button>
                    </form>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    No categories found. Create one!
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create Category Form -->
    <div class="glass-panel rounded-2xl border border-gray-800 p-6 h-fit">
        <h3 class="text-xl font-semibold text-white mb-6">Create Category</h3>
        
        <form action="/admin/categories" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Category Name</label>
                <input type="text" name="name" required
                    class="w-full bg-darker border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                    placeholder="e.g. Productivity">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Icon URL (Optional)</label>
                <input type="url" name="icon_url"
                    class="w-full bg-darker border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                    placeholder="https://example.com/icon.png">
                @error('icon_url')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-medium py-3 px-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all">
                Create Category
            </button>
        </form>
    </div>
</div>
@endsection

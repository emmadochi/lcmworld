@extends('layouts.admin')

@section('title', 'Categories Manager')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-white tracking-tight">Categories Manager</h2>
        <p class="text-gray-400 mt-1">Organize and manage catalog categories for mobile apps</p>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-between">
    <div class="flex items-center">
        <i class="fa-solid fa-circle-check mr-3 text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">&times;</button>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Categories List -->
    <div class="lg:col-span-2 glass-panel rounded-2xl border border-gray-800 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-white flex items-center">
                <i class="fa-solid fa-layer-group text-blue-500 mr-2.5"></i>
                Active Categories ({{ count($categories) }})
            </h3>
            <span class="text-xs text-gray-400 bg-gray-800/80 px-3 py-1.5 rounded-lg border border-gray-700">
                Syncs dynamically with mobile app
            </span>
        </div>
        
        <div class="space-y-3">
            @forelse($categories as $category)
                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-900/60 border border-gray-800/80 hover:border-gray-700 transition-all">
                    <div class="flex items-center space-x-4">
                        <div class="w-11 h-11 rounded-xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-400 shrink-0">
                            @if($category->icon_url)
                                <img src="{{ $category->icon_url }}" alt="{{ $category->name }}" class="w-6 h-6 object-cover rounded">
                            @else
                                <i class="fa-solid fa-tag text-lg"></i>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <h4 class="text-white font-semibold text-base">{{ $category->name }}</h4>
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                    {{ $category->mobile_apps_count ?? 0 }} {{ Str::plural('App', $category->mobile_apps_count ?? 0) }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Category ID: #{{ $category->id }} • 
                                @if($category->icon_url)
                                    <span class="text-gray-400 truncate max-w-xs inline-block align-bottom">{{ $category->icon_url }}</span>
                                @else
                                    <span class="text-gray-600 italic">No custom icon URL</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <!-- Edit Button -->
                        <button type="button" 
                            onclick="openEditCategoryModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->icon_url ?? '') }}')"
                            class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:text-white hover:bg-gray-700 border border-gray-700 text-xs font-medium flex items-center transition-all">
                            <i class="fa-solid fa-pen-to-square mr-1.5 text-blue-400"></i> Edit
                        </button>
                        
                        <!-- Delete Button -->
                        <form action="/admin/categories/{{ $category->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category? Any linked apps will remain safe.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors"
                                title="Delete category">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="fa-solid fa-tags text-4xl mb-3 text-gray-600"></i>
                    <p class="text-base font-medium">No categories created yet.</p>
                    <p class="text-sm text-gray-600 mt-1">Use the form on the right to add your first category.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create Category Form -->
    <div class="glass-panel rounded-2xl border border-gray-800 p-6 h-fit sticky top-6">
        <h3 class="text-xl font-semibold text-white mb-2 flex items-center">
            <i class="fa-solid fa-plus-circle text-emerald-400 mr-2.5"></i>
            Create New Category
        </h3>
        <p class="text-xs text-gray-400 mb-6">Will instantly show as a filter in the mobile app</p>
        
        <form action="/admin/categories" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Category Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" required
                    class="w-full bg-darker border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                    placeholder="e.g. Productivity, Communication, Tools">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Icon URL <span class="text-xs text-gray-500 font-normal">(Optional)</span></label>
                <input type="url" name="icon_url"
                    class="w-full bg-darker border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                    placeholder="https://example.com/icons/productivity.png">
                @error('icon_url')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-medium py-3 px-4 rounded-xl shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center">
                <i class="fa-solid fa-plus mr-2"></i> Create Category
            </button>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-md rounded-2xl border border-gray-700 p-6 shadow-2xl relative">
        <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-800">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fa-solid fa-pen-to-square text-blue-400 mr-2"></i>
                Edit Category
            </h3>
            <button onclick="closeEditCategoryModal()" class="text-gray-400 hover:text-white text-2xl leading-none">&times;</button>
        </div>

        <form id="editCategoryForm" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-xs font-medium text-gray-300 mb-1">Category Name <span class="text-red-400">*</span></label>
                <input type="text" id="edit_category_name" name="name" required
                    class="w-full bg-darker border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-blue-500 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-300 mb-1">Icon URL (Optional)</label>
                <input type="url" id="edit_category_icon_url" name="icon_url"
                    class="w-full bg-darker border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-blue-500 transition-colors">
            </div>

            <div class="flex justify-end space-x-3 pt-3 border-t border-gray-800">
                <button type="button" onclick="closeEditCategoryModal()" class="px-4 py-2 rounded-xl bg-gray-800 text-gray-300 hover:bg-gray-700 text-sm font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-medium text-sm transition-all shadow-lg shadow-blue-600/30">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditCategoryModal(id, name, iconUrl) {
        document.getElementById('editCategoryForm').action = '/admin/categories/' + id + '/update';
        document.getElementById('edit_category_name').value = name;
        document.getElementById('edit_category_icon_url').value = iconUrl;
        document.getElementById('editCategoryModal').classList.remove('hidden');
    }

    function closeEditCategoryModal() {
        document.getElementById('editCategoryModal').classList.add('hidden');
    }
</script>
@endsection

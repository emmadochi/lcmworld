@extends('layouts.admin')

@section('title', 'App Manager')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-white mb-2">App Manager</h2>
        <p class="text-gray-400">Upload new apps and manage existing ones.</p>
    </div>
    <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
        <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Upload App
    </button>
</div>

<!-- Apps Table -->
<div class="glass-panel rounded-xl border border-gray-800 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-800/50 border-b border-gray-800 text-gray-400 text-sm">
            <tr>
                <th class="px-6 py-4 font-medium">App</th>
                <th class="px-6 py-4 font-medium">Category</th>
                <th class="px-6 py-4 font-medium">Version</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800 text-sm">
            @forelse($apps as $app)
            <tr class="hover:bg-gray-800/30 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-gray-800 mr-4 overflow-hidden">
                            @if($app->icon_url)
                                <img src="{{ Str::startsWith($app->icon_url, ['http://', 'https://']) ? $app->icon_url : asset('storage/' . $app->icon_url) }}" alt="{{ $app->app_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center font-bold text-white">
                                    {{ substr($app->app_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="font-medium text-white">{{ $app->app_name }}</div>
                            <div class="text-gray-500 text-xs mt-1">{{ \Illuminate\Support\Str::limit($app->description, 30) }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-400">
                    {{ $app->category ? $app->category->name : 'Uncategorized' }}
                </td>
                <td class="px-6 py-4 text-gray-400">
                    {{ $app->version }}
                </td>
                <td class="px-6 py-4 text-right">
                    <button class="text-gray-400 hover:text-blue-400 mr-3"><i class="fa-solid fa-pen"></i></button>
                    <button class="text-gray-400 hover:text-red-400"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                    No apps found. Click "Upload App" to add one.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
    <div class="glass-panel w-full max-w-lg rounded-xl border border-gray-700 shadow-2xl">
        <div class="flex items-center justify-between p-6 border-b border-gray-800">
            <h3 class="text-lg font-medium text-white">Upload New App</h3>
            <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-gray-400 hover:text-white">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <form id="uploadForm" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">App Name</label>
                        <input type="text" name="app_name" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Package Name (e.g. com.company.app)</label>
                        <input type="text" name="package_name" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Description</label>
                    <textarea name="description" rows="3" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Category</label>
                        <select name="category_id" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Version</label>
                        <input type="text" name="version" value="1.0.0" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">App Icon (Image)</label>
                    <input type="file" name="icon" accept="image/*" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600/20 file:text-blue-400 hover:file:bg-blue-600/30">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">APK File</label>
                    <input type="file" name="apk" accept=".apk" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600/20 file:text-blue-400 hover:file:bg-blue-600/30">
                </div>

                <!-- Progress Bar -->
                <div id="progressContainer" class="hidden mt-4">
                    <div class="flex justify-between text-xs text-gray-400 mb-1">
                        <span>Uploading...</span>
                        <span id="progressText">0%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div id="progressBar" class="bg-blue-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" id="submitBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        Upload & Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    let formData = new FormData(this);
    let submitBtn = document.getElementById('submitBtn');
    let progressContainer = document.getElementById('progressContainer');
    let progressBar = document.getElementById('progressBar');
    let progressText = document.getElementById('progressText');
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Uploading...';
    progressContainer.classList.remove('hidden');

    // Use XMLHttpRequest for progress tracking
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/admin/apps/upload', true);
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            let percentComplete = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percentComplete + '%';
            progressText.innerText = percentComplete + '%';
        }
    };

    xhr.onload = function() {
        if (xhr.status === 200) {
            alert('App uploaded successfully!');
            window.location.reload();
        } else {
            alert('Upload failed: ' + xhr.responseText);
            resetForm();
        }
    };

    xhr.onerror = function() {
        alert('An error occurred during upload.');
        resetForm();
    };

    xhr.send(formData);

    function resetForm() {
        submitBtn.disabled = false;
        submitBtn.innerText = 'Upload & Save';
        progressContainer.classList.add('hidden');
        progressBar.style.width = '0%';
    }
});
</script>
@endsection

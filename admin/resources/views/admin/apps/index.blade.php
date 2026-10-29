@extends('layouts.admin')

@section('title', 'App Manager')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-white mb-2">App Manager</h2>
        <p class="text-gray-400">Upload new apps and manage existing catalog packages.</p>
    </div>
    <button type="button" onclick="openUploadModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-lg shadow-blue-600/20">
        <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Upload App
    </button>
</div>

<!-- Apps Table -->
<div class="glass-panel rounded-xl border border-gray-800 overflow-x-auto shadow-xl">
    <table class="w-full text-left min-w-[680px]">
        <thead class="bg-gray-800/50 border-b border-gray-800 text-gray-400 text-sm">
            <tr>
                <th class="px-6 py-4 font-medium">App</th>
                <th class="px-6 py-4 font-medium">Category</th>
                <th class="px-6 py-4 font-medium">Version & Size</th>
                <th class="px-6 py-4 font-medium">Screenshots</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800 text-sm">
            @forelse($apps as $app)
            <tr class="hover:bg-gray-800/30 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-xl bg-gray-800 mr-4 overflow-hidden flex-shrink-0 border border-gray-700">
                            @if($app->icon_url)
                                <img src="{{ Str::startsWith($app->icon_url, ['http://', 'https://']) ? $app->icon_url : asset('storage/' . $app->icon_url) }}" alt="{{ $app->app_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center font-bold text-white text-lg">
                                    {{ substr($app->app_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="font-semibold text-white text-base">{{ $app->app_name }}</div>
                            <div class="text-blue-400 font-mono text-xs">{{ $app->package_name ?? 'No package ID' }}</div>
                            <div class="text-gray-500 text-xs mt-0.5">{{ \Illuminate\Support\Str::limit($app->description, 36) }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-400">
                    <span class="px-2.5 py-1 bg-gray-800 border border-gray-700 rounded-md text-xs text-gray-300">
                        {{ $app->category ? $app->category->name : 'General' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-300">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-blue-950/60 border border-blue-800/50 rounded font-mono text-xs text-blue-300 font-medium">
                            v{{ $app->version }}
                        </span>
                        @if($app->file_size)
                            <span class="text-xs text-gray-400 font-medium">{{ $app->file_size }}</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-400">
                    @php
                        $count = is_array($app->screenshots) ? count($app->screenshots) : 0;
                    @endphp
                    @if($count > 0)
                        <span class="text-xs text-emerald-400 font-medium"><i class="fa-regular fa-images mr-1"></i>{{ $count }} previews</span>
                    @else
                        <span class="text-xs text-gray-600">No previews</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <!-- Featured Toggle -->
                    <form action="{{ url('/admin/apps/' . $app->id . '/toggle-featured') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-lg transition-colors mr-2 {{ $app->is_featured ? 'text-amber-400 bg-amber-400/10 hover:bg-amber-400/20' : 'text-gray-500 hover:text-amber-400 hover:bg-gray-800' }}" title="{{ $app->is_featured ? 'Featured App (Click to unfeature)' : 'Mark as Featured' }}">
                            <i class="fa-{{ $app->is_featured ? 'solid' : 'regular' }} fa-star"></i>
                        </button>
                    </form>

                    <!-- Edit App Button -->
                    <button type="button" 
                        onclick="openEditModal({{ $app->id }})" 
                        id="edit-btn-{{ $app->id }}"
                        data-app="{{ base64_encode(json_encode($app)) }}"
                        class="text-gray-400 hover:text-blue-400 p-1.5 rounded-lg hover:bg-blue-500/10 transition-colors mr-2 cursor-pointer" 
                        title="Edit App Details">
                        <i class="fa-solid fa-pen pointer-events-none"></i>
                    </button>
                    <script type="application/json" id="app-data-{{ $app->id }}">
                        {!! json_encode($app) !!}
                    </script>

                    <!-- Delete App Form -->
                    <form action="{{ url('/admin/apps/' . $app->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($app->app_name) }}\'? This will remove the APK and screenshots permanently.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-400 p-1.5 rounded-lg hover:bg-red-500/10 transition-colors" title="Delete App">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    <i class="fa-solid fa-box-open text-3xl mb-3 text-gray-600"></i>
                    <p>No apps found in the catalog. Click "Upload App" to add one.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="glass-panel w-full max-w-2xl rounded-2xl border border-gray-700 shadow-2xl my-8">
        <div class="flex items-center justify-between p-6 border-b border-gray-800">
            <div>
                <h3 class="text-lg font-bold text-white">Upload New App Package</h3>
                <p class="text-xs text-gray-400 mt-0.5">Distribute a Play Store-grade APK package to LCM World</p>
            </div>
            <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-gray-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <form id="uploadForm" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">App Name <span class="text-red-400">*</span></label>
                        <input type="text" name="app_name" placeholder="e.g. LCM Mobile Portal" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3.5 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Package Name <span class="text-red-400">*</span></label>
                        <input type="text" name="package_name" placeholder="e.g. org.lifechangerstouch.portal" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3.5 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Description <span class="text-red-400">*</span></label>
                    <textarea name="description" rows="3" placeholder="Provide a detailed overview of the app's features and purpose..." required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3.5 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Category</label>
                        <select name="category_id" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3.5 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Version <span class="text-red-400">*</span></label>
                        <input type="text" name="version" value="1.0.0" placeholder="e.g. 1.0.0" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3.5 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm font-mono">
                    </div>
                </div>

                <!-- What's New -->
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">What's New / Changelog (Optional)</label>
                    <textarea name="whats_new" rows="2" placeholder="e.g. • Performance improvements and bug fixes&#10;• New dark mode interface" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3.5 py-2 text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">App Icon (Max 2MB)</label>
                        <input type="file" name="icon" accept="image/*" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-1.5 text-gray-400 text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600/20 file:text-blue-400 hover:file:bg-blue-600/30">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">APK File <span class="text-red-400">*</span></label>
                        <input type="file" name="apk" accept=".apk" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-1.5 text-gray-400 text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600/20 file:text-blue-400 hover:file:bg-blue-600/30">
                    </div>
                </div>

                <!-- Multiple Screenshots -->
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">App Screenshots & Previews (Up to 4 images, Max 3MB each)</label>
                    <input type="file" name="screenshots[]" accept="image/*" multiple class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-1.5 text-gray-400 text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600/20 file:text-blue-400 hover:file:bg-blue-600/30">
                    <p class="text-[11px] text-gray-500 mt-1">Select 1 to 4 screenshots to display in the mobile app's preview carousel.</p>
                </div>

                <!-- Progress Bar -->
                <div id="progressContainer" class="hidden mt-4 bg-gray-900/60 p-3 rounded-lg border border-gray-800">
                    <div class="flex justify-between text-xs text-gray-400 mb-1.5">
                        <span class="flex items-center"><i class="fa-solid fa-spinner fa-spin mr-1.5 text-blue-400"></i> Uploading package to server...</span>
                        <span id="progressText" class="font-bold text-blue-400">0%</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-2.5 overflow-hidden">
                        <div id="progressBar" class="bg-gradient-to-r from-blue-500 to-indigo-500 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-2 border-t border-gray-800">
                    <button type="button" onclick="closeUploadModal()" class="px-5 py-2 rounded-lg text-sm font-medium text-gray-400 hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors shadow-lg shadow-blue-600/20">
                        Upload & Publish App
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 p-4 overflow-y-auto hidden items-center justify-center" onclick="if(event.target === this) closeEditModal()">
    <div class="glass-panel w-full max-w-2xl rounded-2xl border border-gray-700 shadow-2xl my-8">
        <div class="flex items-center justify-between p-6 border-b border-gray-800">
            <div>
                <h3 class="text-lg font-bold text-white">Edit App Package</h3>
                <p class="text-xs text-gray-400 mt-0.5">Update catalog metadata and changelog</p>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">App Name <span class="text-red-400">*</span></label>
                        <input type="text" id="edit_app_name" name="app_name" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3.5 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Package Name</label>
                        <input type="text" id="edit_package_name" name="package_name" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3.5 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Description <span class="text-red-400">*</span></label>
                    <textarea id="edit_description" name="description" rows="3" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3.5 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Category</label>
                        <select id="edit_category_id" name="category_id" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3.5 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Version <span class="text-red-400">*</span></label>
                        <input type="text" id="edit_version" name="version" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3.5 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">What's New / Changelog</label>
                    <textarea id="edit_whats_new" name="whats_new" rows="2" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3.5 py-2 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Update Icon (Optional, Max 2MB)</label>
                        <input type="file" name="icon" accept="image/*" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-1.5 text-gray-400 text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600/20 file:text-blue-400 hover:file:bg-blue-600/30">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1">Update APK File (Optional)</label>
                        <input type="file" name="apk" accept=".apk" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-1.5 text-gray-400 text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600/20 file:text-blue-400 hover:file:bg-blue-600/30">
                    </div>
                </div>

                <!-- Real-Time Edit Progress Bar -->
                <div id="editProgressContainer" class="hidden mt-4 bg-gray-900/80 p-3.5 rounded-xl border border-blue-500/30 shadow-inner">
                    <div class="flex justify-between items-center text-xs text-gray-300 mb-2">
                        <span class="flex items-center font-medium">
                            <i class="fa-solid fa-spinner fa-spin mr-2 text-blue-400" id="editProgressIcon"></i>
                            <span id="editProgressLabel">Uploading updated package...</span>
                        </span>
                        <span id="editProgressText" class="font-mono text-blue-400 font-bold text-sm">0%</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-3 overflow-hidden p-0.5 border border-gray-700">
                        <div id="editProgressBar" class="bg-gradient-to-r from-blue-500 via-indigo-500 to-emerald-400 h-full rounded-full transition-all duration-150" style="width: 0%"></div>
                    </div>
                    <div class="text-[11px] text-gray-500 mt-2 flex items-center justify-between">
                        <span>Please keep this window open while transferring.</span>
                        <span id="editUploadSize" class="font-mono text-gray-400"></span>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-2 border-t border-gray-800">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2 rounded-lg text-sm font-medium text-gray-400 hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="editSubmitBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors shadow-lg shadow-blue-600/20">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openUploadModal() {
    var modal = document.getElementById('uploadModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeUploadModal() {
    var modal = document.getElementById('uploadModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function openEditModal(appOrId) {
    var modal = document.getElementById('editModal');
    if (!modal) {
        alert('Edit modal element not found in DOM');
        return;
    }
    
    var app = null;

    // 1. Try decoding from the button's data-app attribute
    if (typeof appOrId === 'number' || typeof appOrId === 'string') {
        var btn = document.getElementById('edit-btn-' + appOrId);
        if (btn && btn.dataset.app) {
            try {
                app = JSON.parse(atob(btn.dataset.app));
            } catch (e) {
                console.warn('Base64 decode failed:', e);
            }
        }

        // 2. Fallback to json script element
        if (!app) {
            var appElement = document.getElementById('app-data-' + appOrId);
            if (appElement) {
                try {
                    app = JSON.parse(appElement.textContent);
                } catch (e) {
                    console.error('Failed to parse app data', e);
                }
            }
        }
    } else if (typeof appOrId === 'object' && appOrId !== null) {
        app = appOrId;
    }

    if (!app) {
        alert('App data could not be retrieved. Please refresh and try again.');
        return;
    }

    var form = document.getElementById('editForm');
    if (form) {
        form.action = '/admin/apps/' + app.id + '/update';
    }

    var nameInput = document.getElementById('edit_app_name');
    if (nameInput) nameInput.value = app.app_name || '';

    var pkgInput = document.getElementById('edit_package_name');
    if (pkgInput) pkgInput.value = app.package_name || '';

    var descInput = document.getElementById('edit_description');
    if (descInput) descInput.value = app.description || '';

    var catInput = document.getElementById('edit_category_id');
    if (catInput) catInput.value = app.category_id || '';

    var verInput = document.getElementById('edit_version');
    if (verInput) verInput.value = app.version || '';

    var changelogInput = document.getElementById('edit_whats_new');
    if (changelogInput) changelogInput.value = app.whats_new || '';

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEditModal() {
    var modal = document.getElementById('editModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
        closeUploadModal();
    }
});

document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Client-side file size check for the app icon (2MB)
    let iconInput = this.querySelector('input[name="icon"]');
    if (iconInput && iconInput.files.length > 0) {
        let iconFile = iconInput.files[0];
        if (iconFile.size > 2 * 1024 * 1024) {
            alert('Error: The App Icon image must not exceed 2MB.');
            return;
        }
    }

    // Client-side check for screenshots (Max 4, each max 3MB)
    let screenshotsInput = this.querySelector('input[name="screenshots[]"]');
    if (screenshotsInput && screenshotsInput.files.length > 0) {
        if (screenshotsInput.files.length > 4) {
            alert('Error: You can upload a maximum of 4 screenshots.');
            return;
        }
        for (let i = 0; i < screenshotsInput.files.length; i++) {
            if (screenshotsInput.files[i].size > 3 * 1024 * 1024) {
                alert('Error: Screenshot "' + screenshotsInput.files[i].name + '" exceeds 3MB limit.');
                return;
            }
        }
    }

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
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            let percentComplete = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percentComplete + '%';
            progressText.innerText = percentComplete + '%';
        }
    };

    xhr.onload = function() {
        let response = {};
        try {
            response = JSON.parse(xhr.responseText);
        } catch (e) {}

        if (xhr.status === 200) {
            alert(response.message || 'App published successfully!');
            window.location.reload();
        } else {
            let errorMsg = response.message || 'Upload failed.';
            if (response.errors) {
                errorMsg += '\n' + Object.values(response.errors).flat().join('\n');
            }
            alert(errorMsg);
            resetForm();
        }
    };

    xhr.onerror = function() {
        alert('A network error occurred during upload.');
        resetForm();
    };

    xhr.send(formData);

    function resetForm() {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Upload & Publish App';
        progressContainer.classList.add('hidden');
        progressBar.style.width = '0%';
    }
});

// Real-Time Progress Bar for App Updates
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Check optional icon file size (2MB)
    let iconInput = this.querySelector('input[name="icon"]');
    if (iconInput && iconInput.files.length > 0) {
        if (iconInput.files[0].size > 2 * 1024 * 1024) {
            alert('Error: The App Icon image must not exceed 2MB.');
            return;
        }
    }

    let formData = new FormData(this);
    let submitBtn = document.getElementById('editSubmitBtn');
    let progressContainer = document.getElementById('editProgressContainer');
    let progressBar = document.getElementById('editProgressBar');
    let progressText = document.getElementById('editProgressText');
    let progressLabel = document.getElementById('editProgressLabel');
    let uploadSize = document.getElementById('editUploadSize');

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Uploading & Updating...';
    progressContainer.classList.remove('hidden');
    progressBar.style.width = '0%';
    progressText.innerText = '0%';
    progressLabel.innerText = 'Uploading updated package...';
    uploadSize.innerText = '';

    let xhr = new XMLHttpRequest();
    xhr.open('POST', this.action, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            let percentComplete = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percentComplete + '%';
            progressText.innerText = percentComplete + '%';

            let loadedMb = (e.loaded / (1024 * 1024)).toFixed(1);
            let totalMb = (e.total / (1024 * 1024)).toFixed(1);
            uploadSize.innerText = loadedMb + ' MB / ' + totalMb + ' MB';

            if (percentComplete >= 100) {
                progressLabel.innerText = 'Processing & saving on server...';
                progressText.innerText = '100%';
            }
        }
    };

    xhr.onload = function() {
        let response = {};
        try {
            response = JSON.parse(xhr.responseText);
        } catch (err) {}

        if (xhr.status === 200) {
            progressLabel.innerText = 'Update completed successfully!';
            progressBar.style.width = '100%';
            setTimeout(function() {
                alert(response.message || 'App updated successfully!');
                window.location.reload();
            }, 350);
        } else {
            let errorMsg = response.message || 'Update failed.';
            if (response.errors) {
                errorMsg += '\n' + Object.values(response.errors).flat().join('\n');
            }
            alert(errorMsg);
            resetEditForm();
        }
    };

    xhr.onerror = function() {
        alert('A network error occurred while uploading update. Please check connection and try again.');
        resetEditForm();
    };

    xhr.send(formData);

    function resetEditForm() {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Save Changes';
        progressContainer.classList.add('hidden');
        progressBar.style.width = '0%';
        progressText.innerText = '0%';
        uploadSize.innerText = '';
    }
});
</script>
@endsection

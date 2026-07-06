@extends('layouts.admin')

@section('title', 'Documents')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-white mb-2">Documents Library</h2>
        <p class="text-gray-400">Manage your standalone documents and chat response data.</p>
    </div>
    <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
        <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Upload Documents
    </button>
</div>

@if(session('success'))
<div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<!-- Documents Table -->
<div class="glass-panel rounded-xl border border-gray-800 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-800/50 border-b border-gray-800 text-gray-400 text-sm">
            <tr>
                <th class="px-6 py-4 font-medium">Title</th>
                <th class="px-6 py-4 font-medium">File Type</th>
                <th class="px-6 py-4 font-medium">Uploaded At</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800 text-sm">
            @forelse($documents as $doc)
            <tr class="hover:bg-gray-800/30 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-gray-800 mr-4 flex items-center justify-center text-gray-400">
                            @if(str_contains($doc->file_type, 'image'))
                                <i class="fa-solid fa-image text-xl"></i>
                            @elseif(str_contains($doc->file_type, 'pdf'))
                                <i class="fa-solid fa-file-pdf text-xl"></i>
                            @else
                                <i class="fa-solid fa-file text-xl"></i>
                            @endif
                        </div>
                        <div>
                            <div class="font-medium text-white">{{ $doc->title }}</div>
                            <div class="text-gray-500 text-xs mt-1">{{ $doc->file_name }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-400">
                    {{ $doc->file_type }}
                </td>
                <td class="px-6 py-4 text-gray-400">
                    {{ $doc->created_at->format('M d, Y') }}
                </td>
                <td class="px-6 py-4 text-right flex justify-end gap-3">
                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-gray-400 hover:text-blue-400">
                        <i class="fa-solid fa-download"></i>
                    </a>
                    <form action="/admin/documents/{{ $doc->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-400"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                    No documents found. Click "Upload Documents" to add some.
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
            <h3 class="text-lg font-medium text-white">Upload Documents</h3>
            <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-gray-400 hover:text-white">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <form id="uploadForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Select Files (Multiple allowed)</label>
                    <input type="file" name="documents[]" multiple required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600/20 file:text-blue-400 hover:file:bg-blue-600/30">
                    <p class="text-xs text-gray-500 mt-2">You can select images, PDFs, Word docs, etc.</p>
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
                        Upload
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
    xhr.open('POST', '/admin/documents/upload', true);
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
            let response = JSON.parse(xhr.responseText);
            if(response.success) {
                alert(response.message);
                window.location.reload();
            } else {
                alert('Upload failed.');
                resetForm();
            }
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
        submitBtn.innerText = 'Upload';
        progressContainer.classList.add('hidden');
        progressBar.style.width = '0%';
    }
});
</script>
@endsection

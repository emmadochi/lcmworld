<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Lifechangers Apps</title>
    <!-- Tailwind CSS (CDN for rapid development without build step) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1d4ed8',
                        dark: '#0f172a',
                        darker: '#020617',
                        card: '#1e293b'
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-darker text-gray-200 antialiased h-screen flex overflow-hidden">
    
    <!-- Mobile Sidebar Backdrop -->
    <div id="mobileSidebarBackdrop" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-40 hidden md:hidden transition-opacity" onclick="toggleMobileSidebar()"></div>

    <!-- Mobile Slide-Over Sidebar Drawer -->
    <aside id="mobileSidebar" class="fixed inset-y-0 left-0 w-72 glass-panel z-50 flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden border-r border-gray-800 bg-slate-900/95 shadow-2xl">
        <div class="h-16 flex items-center justify-between px-5 border-b border-gray-800">
            <h1 class="text-lg font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-400 flex items-center">
                <img src="{{ asset('images/logo_icon.png') }}" alt="Lifechangers Apps" class="h-8 w-8 mr-2.5 rounded-lg object-contain">
                Lifechangers Apps
            </h1>
            <button onclick="toggleMobileSidebar()" class="text-gray-400 hover:text-white p-1.5 rounded-lg hover:bg-gray-800">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="/admin/dashboard" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/dashboard') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-chart-pie w-6"></i>
                <span class="font-medium ml-3">Dashboard</span>
            </a>
            <a href="/admin/apps" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/apps*') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-brands fa-app-store w-6"></i>
                <span class="font-medium ml-3">App Manager</span>
            </a>
            <a href="/admin/categories" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/categories*') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-tags w-6"></i>
                <span class="font-medium ml-3">Categories</span>
            </a>
            <a href="/admin/analytics" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/analytics*') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-chart-line w-6"></i>
                <span class="font-medium ml-3">Analytics</span>
            </a>
            <a href="/admin/users" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/users*') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-users w-6"></i>
                <span class="font-medium ml-3">Users</span>
            </a>
            <a href="/admin/documents" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/documents*') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-file-lines w-6"></i>
                <span class="font-medium ml-3">Documents</span>
            </a>
            <a href="/admin/beta-feedback" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/beta-feedback*') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-vial-virus w-6"></i>
                <span class="font-medium ml-3">Beta Feedback</span>
            </a>
        </nav>
        <div class="p-4 border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-400 hover:text-white transition-colors">
                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Desktop Sidebar -->
    <aside class="w-64 glass-panel h-full flex flex-col hidden md:flex border-r border-gray-800">
        <div class="h-16 flex items-center px-6 border-b border-gray-800">
            <h1 class="text-lg font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-400 flex items-center">
                <img src="{{ asset('images/logo_icon.png') }}" alt="Lifechangers Apps Logo" class="h-8 w-8 mr-2.5 rounded-lg object-contain">
                Lifechangers Apps
            </h1>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="/admin/dashboard" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/dashboard') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-chart-pie w-6"></i>
                <span class="font-medium ml-3">Dashboard</span>
            </a>
            <a href="/admin/apps" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/apps*') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-brands fa-app-store w-6"></i>
                <span class="font-medium ml-3">App Manager</span>
            </a>
            <a href="/admin/categories" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/categories*') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-tags w-6"></i>
                <span class="font-medium ml-3">Categories</span>
            </a>
            <a href="/admin/analytics" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/analytics*') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-chart-line w-6"></i>
                <span class="font-medium ml-3">Analytics</span>
            </a>
            <a href="/admin/users" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/users*') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-users w-6"></i>
                <span class="font-medium ml-3">Users</span>
            </a>
            <a href="/admin/documents" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/documents*') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-file-lines w-6"></i>
                <span class="font-medium ml-3">Documents</span>
            </a>
            <a href="/admin/beta-feedback" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/beta-feedback*') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-vial-virus w-6"></i>
                <span class="font-medium ml-3">Beta Feedback</span>
            </a>
        </nav>
        <div class="p-4 border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-400 hover:text-white transition-colors">
                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden">
        <!-- Topbar -->
        <header class="h-16 glass-panel flex items-center justify-between px-4 sm:px-6 border-b border-gray-800">
            <div class="flex items-center">
                <button type="button" onclick="toggleMobileSidebar()" class="md:hidden text-gray-400 hover:text-white p-2 mr-2 rounded-lg hover:bg-gray-800 transition-colors" title="Toggle Navigation Menu">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <span class="md:hidden font-bold text-sm bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-400">
                    Lifechangers Apps
                </span>
            </div>
            <div class="flex items-center space-x-3 sm:space-x-4 ml-auto">
                <a href="/admin/clear-cache" title="Clear Cache & Sync DB" class="text-xs px-2.5 py-1 rounded bg-blue-600/20 text-blue-400 hover:bg-blue-600/30 transition-colors hidden sm:inline-flex items-center">
                    <i class="fa-solid fa-rotate mr-1.5 text-[10px]"></i> Sync & Cache
                </a>
                <button class="text-gray-400 hover:text-white relative p-1.5">
                    <i class="fa-solid fa-bell"></i>
                    <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500 flex items-center justify-center font-bold text-xs text-white shadow-md">
                    LA
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-between shadow-lg">
                    <div class="flex items-center">
                        <i class="fa-solid fa-circle-check mr-3 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400/70 hover:text-emerald-300">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 flex items-center justify-between shadow-lg">
                    <div class="flex items-center">
                        <i class="fa-solid fa-triangle-exclamation mr-3 text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-400/70 hover:text-red-300">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
            @yield('content')
        </div>
    </main>

    <!-- Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const backdrop = document.getElementById('mobileSidebarBackdrop');
            if (!sidebar || !backdrop) return;
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
    @yield('scripts')
</body>
</html>

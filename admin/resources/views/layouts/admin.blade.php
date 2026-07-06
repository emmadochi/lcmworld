<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - LCMWorld</title>
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
    
    <!-- Sidebar -->
    <aside class="w-64 glass-panel h-full flex flex-col hidden md:flex border-r border-gray-800">
        <div class="h-16 flex items-center px-6 border-b border-gray-800">
            <h1 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500 flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="LCMWorld Logo" class="h-8 w-8 mr-2 object-contain">
                LCMWorld
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
            <a href="/admin/documents" class="flex items-center px-4 py-3 rounded-lg {{ request()->is('admin/documents*') ? 'bg-blue-600/20 text-blue-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-file-lines w-6"></i>
                <span class="font-medium ml-3">Documents</span>
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
        <header class="h-16 glass-panel flex items-center justify-between px-6 border-b border-gray-800">
            <button class="md:hidden text-gray-400 hover:text-white">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <div class="flex items-center space-x-4 ml-auto">
                <button class="text-gray-400 hover:text-white relative">
                    <i class="fa-solid fa-bell"></i>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500 flex items-center justify-center font-bold text-sm text-white">
                    AD
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 overflow-y-auto p-6 lg:p-8">
            @yield('content')
        </div>
    </main>

    <!-- Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
</body>
</html>

@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-white mb-2">Dashboard Overview</h2>
    <p class="text-gray-400">Welcome back! Here's what's happening with your apps today.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8">
    <!-- Stat Card 1: Apps -->
    <div class="glass-panel p-4 sm:p-6 rounded-xl border border-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-400 font-medium">Catalog Apps</h3>
            <div class="w-10 h-10 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center">
                <i class="fa-solid fa-mobile-screen"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-white">{{ $totalApps ?? 0 }}</div>
        <div class="mt-2 text-xs text-blue-400"><a href="/admin/apps" class="hover:underline">Manage apps &rarr;</a></div>
    </div>
    
    <!-- Stat Card 2: Downloads -->
    <div class="glass-panel p-6 rounded-xl border border-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-400 font-medium">Total Downloads</h3>
            <div class="w-10 h-10 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                <i class="fa-solid fa-download"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-white">{{ $totalDownloads ?? 0 }}</div>
        <div class="mt-2 text-xs text-emerald-400"><a href="/admin/analytics" class="hover:underline">View telemetry &rarr;</a></div>
    </div>

    <!-- Stat Card 3: Views -->
    <div class="glass-panel p-6 rounded-xl border border-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-400 font-medium">Catalog Views</h3>
            <div class="w-10 h-10 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center">
                <i class="fa-solid fa-eye"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-white">{{ $totalViews ?? 0 }}</div>
        <div class="mt-2 text-xs text-purple-400">Total impressions</div>
    </div>

    <!-- Stat Card 4: Categories -->
    <div class="glass-panel p-6 rounded-xl border border-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-400 font-medium">Categories</h3>
            <div class="w-10 h-10 rounded-lg bg-pink-500/20 text-pink-400 flex items-center justify-center">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-white">{{ $totalCategories ?? 0 }}</div>
        <div class="mt-2 text-xs text-pink-400"><a href="/admin/categories" class="hover:underline">Manage categories &rarr;</a></div>
    </div>
</div>

<!-- Two Column Layout: Recent Apps & Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Apps (2 cols) -->
    <div class="lg:col-span-2 glass-panel rounded-xl border border-gray-800 p-6 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-white">Recently Added Apps</h3>
                <p class="text-xs text-gray-400">Latest APK releases published in Lifechangers Apps</p>
            </div>
            <a href="/admin/apps" class="text-xs text-blue-400 hover:text-blue-300 font-medium">View All &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[480px]">
                <thead class="border-b border-gray-800 text-gray-400 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="pb-3 font-medium">App</th>
                        <th class="pb-3 font-medium">Category</th>
                        <th class="pb-3 font-medium">Version</th>
                        <th class="pb-3 font-medium text-right">Published</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60">
                    @forelse($recentApps as $app)
                    <tr class="hover:bg-gray-800/20 transition-colors">
                        <td class="py-3.5 flex items-center">
                            <div class="w-9 h-9 rounded-lg bg-gray-800 mr-3 overflow-hidden flex-shrink-0 border border-gray-700">
                                @if($app->icon_url)
                                    <img src="{{ Str::startsWith($app->icon_url, ['http://', 'https://']) ? $app->icon_url : asset('storage/' . $app->icon_url) }}" alt="{{ $app->app_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-blue-600 flex items-center justify-center font-bold text-white text-xs">
                                        {{ substr($app->app_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <span class="font-medium text-white block">{{ $app->app_name }}</span>
                                <span class="text-xs text-gray-400 font-mono">{{ $app->package_name ?? 'No package' }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 text-gray-400">
                            <span class="px-2 py-0.5 bg-gray-800 border border-gray-700 rounded text-xs text-gray-300">
                                {{ $app->category ? $app->category->name : 'General' }}
                            </span>
                        </td>
                        <td class="py-3.5">
                            <span class="px-2 py-0.5 bg-blue-950/60 border border-blue-800/50 rounded font-mono text-xs text-blue-300">
                                v{{ $app->version }}
                            </span>
                        </td>
                        <td class="py-3.5 text-right text-xs text-gray-500">
                            {{ $app->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500 text-xs">
                            No apps uploaded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Live Event Feed (1 col) -->
    <div class="glass-panel rounded-xl border border-gray-800 p-6 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-white">Live Telemetry</h3>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
        </div>

        <div class="space-y-4">
            @forelse($recentEvents as $event)
            <div class="flex items-start space-x-3 text-xs p-3 rounded-lg bg-gray-900/40 border border-gray-800">
                <div class="p-2 rounded-lg {{ $event->event_type == 'download' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-blue-500/10 text-blue-400' }} flex-shrink-0 mt-0.5">
                    <i class="fa-solid {{ $event->event_type == 'download' ? 'fa-download' : 'fa-eye' }}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-white truncate">
                        {{ $event->mobileApp ? $event->mobileApp->app_name : 'App' }}
                    </p>
                    <p class="text-gray-400 mt-0.5">
                        {{ ucfirst($event->event_type) }} from <span class="font-mono text-gray-500">{{ $event->ip_address }}</span>
                    </p>
                    <p class="text-[10px] text-gray-500 mt-1">
                        {{ $event->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 text-xs">
                No telemetry recorded yet.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

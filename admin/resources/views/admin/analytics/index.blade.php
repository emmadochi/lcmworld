@extends('layouts.admin')

@section('title', 'Analytics & User Tracking')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-white mb-1 flex items-center">
            <i class="fa-solid fa-chart-line text-blue-400 mr-2.5"></i> Analytics & User Tracking
        </h2>
        <p class="text-gray-400 text-sm">Monitor app downloads, user signups, engagement views, and conversion rates.</p>
    </div>
    
    <!-- Time Range Selector -->
    <div class="flex items-center space-x-1 glass-panel p-1.5 rounded-xl border border-gray-800 bg-slate-900/60 self-start md:self-auto">
        <a href="?days=7" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $days == 7 ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
            Last 7 Days
        </a>
        <a href="?days=14" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $days == 14 ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
            Last 14 Days
        </a>
        <a href="?days=30" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $days == 30 ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
            Last 30 Days
        </a>
    </div>
</div>

<!-- Key Metrics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
    <!-- 1. Total Registered Users -->
    <div class="glass-panel p-5 rounded-2xl border border-gray-800/80 hover:border-purple-500/40 transition-all bg-gradient-to-b from-purple-950/20 to-slate-900/60 shadow-lg">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold uppercase tracking-wider text-purple-300">Registered Users</span>
            <div class="w-8 h-8 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center">
                <i class="fa-solid fa-users text-sm"></i>
            </div>
        </div>
        <div class="text-3xl font-extrabold text-white tracking-tight">{{ number_format($totalUsers) }}</div>
        <div class="mt-2 text-xs flex items-center text-purple-400 font-medium">
            <i class="fa-solid fa-arrow-trend-up mr-1.5"></i>
            <span>+{{ $newUsersInPeriod }} in last {{ $days }} days</span>
        </div>
    </div>

    <!-- 2. Total Downloads -->
    <div class="glass-panel p-5 rounded-2xl border border-gray-800/80 hover:border-blue-500/40 transition-all bg-gradient-to-b from-blue-950/20 to-slate-900/60 shadow-lg">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold uppercase tracking-wider text-blue-300">Total Downloads</span>
            <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center">
                <i class="fa-solid fa-download text-sm"></i>
            </div>
        </div>
        <div class="text-3xl font-extrabold text-white tracking-tight">{{ number_format($totalDownloads) }}</div>
        <div class="mt-2 text-xs text-gray-400 font-medium flex items-center">
            <i class="fa-solid fa-cube mr-1.5 text-blue-400"></i> All-time installs
        </div>
    </div>

    <!-- 3. Total Views -->
    <div class="glass-panel p-5 rounded-2xl border border-gray-800/80 hover:border-cyan-500/40 transition-all bg-gradient-to-b from-cyan-950/20 to-slate-900/60 shadow-lg">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold uppercase tracking-wider text-cyan-300">App Views</span>
            <div class="w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center">
                <i class="fa-solid fa-eye text-sm"></i>
            </div>
        </div>
        <div class="text-3xl font-extrabold text-white tracking-tight">{{ number_format($totalViews) }}</div>
        <div class="mt-2 text-xs text-gray-400 font-medium flex items-center">
            <i class="fa-solid fa-mobile-screen mr-1.5 text-cyan-400"></i> Catalog impressions
        </div>
    </div>

    <!-- 4. Conversion Rate -->
    <div class="glass-panel p-5 rounded-2xl border border-gray-800/80 hover:border-emerald-500/40 transition-all bg-gradient-to-b from-emerald-950/20 to-slate-900/60 shadow-lg">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-300">Conversion Rate</span>
            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                <i class="fa-solid fa-chart-pie text-sm"></i>
            </div>
        </div>
        <div class="text-3xl font-extrabold text-white tracking-tight">{{ $conversionRate }}%</div>
        <div class="mt-2 text-xs text-emerald-400 font-medium flex items-center">
            <i class="fa-solid fa-bolt mr-1.5"></i> Views to downloads
        </div>
    </div>

    <!-- 5. Top App -->
    <div class="glass-panel p-5 rounded-2xl border border-gray-800/80 hover:border-amber-500/40 transition-all bg-gradient-to-b from-amber-950/20 to-slate-900/60 shadow-lg">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold uppercase tracking-wider text-amber-300">Top Performing App</span>
            <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center">
                <i class="fa-solid fa-trophy text-sm"></i>
            </div>
        </div>
        <div class="text-lg font-bold text-white truncate mt-1">{{ $topApp ? $topApp->app_name : 'No downloads yet' }}</div>
        <div class="mt-2 text-xs text-amber-400/90 font-medium flex items-center">
            <i class="fa-solid fa-medal mr-1.5"></i> Most downloaded
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Chart 1: Downloads & Signups Over Time (Spans 2 columns) -->
    <div class="glass-panel p-6 rounded-2xl border border-gray-800 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fa-solid fa-chart-area mr-2 text-blue-400"></i> Downloads & User Growth
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Tracking daily downloads and user registrations over the last {{ $days }} days.</p>
            </div>
            <div class="flex items-center space-x-3 text-xs">
                <span class="flex items-center text-blue-400 font-medium">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 mr-1.5 inline-block"></span> Downloads
                </span>
                <span class="flex items-center text-purple-400 font-medium">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-500 mr-1.5 inline-block"></span> New Users
                </span>
            </div>
        </div>
        <div class="relative h-72">
            <canvas id="growthChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Top Apps by Downloads -->
    <div class="glass-panel p-6 rounded-2xl border border-gray-800">
        <h3 class="text-lg font-bold text-white mb-1 flex items-center">
            <i class="fa-solid fa-ranking-star mr-2 text-purple-400"></i> Top 5 Apps
        </h3>
        <p class="text-xs text-gray-400 mb-4">Most popular apps by all-time downloads.</p>
        <div class="relative h-72">
            <canvas id="topAppsChart"></canvas>
        </div>
    </div>
</div>

<!-- Detailed Tables Grid: Registered Users & Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Registered Users Table -->
    <div class="glass-panel rounded-2xl border border-gray-800 overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-800 flex items-center justify-between bg-slate-900/50">
            <div>
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fa-solid fa-user-check text-purple-400 mr-2"></i> Recent Lifechangers Apps Members
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Users who registered through Lifechangers Apps mobile client.</p>
            </div>
            <a href="/admin/users" class="text-xs font-semibold text-purple-400 hover:text-purple-300 flex items-center transition-colors">
                View All Users <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left">
                <thead class="bg-gray-900/40 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-800">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold">User</th>
                        <th class="px-5 py-3.5 font-semibold">Sign-In Method</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60 text-sm">
                    @forelse($recentUsers as $user)
                    <tr class="hover:bg-gray-800/20 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center">
                                @if(!empty($user->avatar))
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-9 h-9 rounded-full object-cover border border-purple-500/30 mr-3">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-purple-600 to-indigo-600 text-white font-bold text-xs flex items-center justify-center mr-3 shadow-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="text-white font-semibold text-sm">{{ $user->name }}</div>
                                    <div class="text-gray-400 text-xs">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            @if(!empty($user->google_id))
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">
                                    <i class="fa-brands fa-google mr-1.5 text-xs"></i> Google
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                    <i class="fa-solid fa-envelope mr-1.5 text-xs"></i> Email
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right text-gray-400 text-xs font-medium">
                            <div>{{ $user->created_at ? $user->created_at->diffForHumans() : 'N/A' }}</div>
                            <div class="text-gray-500 text-[11px]">{{ $user->created_at ? $user->created_at->format('M d, Y') : '' }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-5 py-8 text-center text-gray-500 text-sm">
                            No registered users found yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent App Activity Table -->
    <div class="glass-panel rounded-2xl border border-gray-800 overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-800 flex items-center justify-between bg-slate-900/50">
            <div>
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fa-solid fa-bolt text-blue-400 mr-2"></i> Real-time App Activity
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Live download and view events across all applications.</p>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-green-500/10 text-green-400 border border-green-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse mr-1.5"></span> Live
            </span>
        </div>
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left">
                <thead class="bg-gray-900/40 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-800">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold">Event</th>
                        <th class="px-5 py-3.5 font-semibold">App</th>
                        <th class="px-5 py-3.5 font-semibold">IP Address</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60 text-sm">
                    @forelse($recentEvents as $event)
                    <tr class="hover:bg-gray-800/20 transition-colors">
                        <td class="px-5 py-3.5">
                            @if($event->event_type == 'download')
                                <span class="px-2.5 py-1 bg-green-500/15 text-green-400 border border-green-500/25 rounded-md text-xs font-semibold inline-flex items-center">
                                    <i class="fa-solid fa-download mr-1.5 text-xs"></i> Download
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-cyan-500/15 text-cyan-400 border border-cyan-500/25 rounded-md text-xs font-semibold inline-flex items-center">
                                    <i class="fa-solid fa-eye mr-1.5 text-xs"></i> View
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-white font-medium">
                            {{ $event->mobileApp ? $event->mobileApp->app_name : 'Lifechangers Apps' }}
                        </td>
                        <td class="px-5 py-3.5 text-gray-400 text-xs font-mono">
                            {{ $event->ip_address }}
                        </td>
                        <td class="px-5 py-3.5 text-right text-gray-400 text-xs">
                            {{ $event->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-500 text-sm">
                            No recent activity found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartLabels = {!! json_encode($chartLabels) !!};
    const downloadsData = {!! json_encode($downloadsData) !!};
    const signupsData = {!! json_encode($signupsData) !!};
    
    const topAppsLabels = {!! json_encode($topAppsLabels) !!};
    const topAppsData = {!! json_encode($topAppsData) !!};

    Chart.defaults.color = '#94a3b8';
    Chart.defaults.scale.grid.color = 'rgba(51, 65, 85, 0.4)';

    // Growth Chart (Downloads vs Signups)
    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Downloads',
                    data: downloadsData,
                    borderColor: '#00E5FF',
                    backgroundColor: 'rgba(0, 229, 255, 0.1)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#00E5FF',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.35
                },
                {
                    label: 'New Users',
                    data: signupsData,
                    borderColor: '#a855f7',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#a855f7',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.35
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: { size: 12, family: 'Inter' }
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#fff',
                    bodyColor: '#cbd5e1',
                    borderColor: '#334155',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    // Top Apps Bar Chart
    new Chart(document.getElementById('topAppsChart'), {
        type: 'bar',
        data: {
            labels: topAppsLabels,
            datasets: [{
                label: 'Downloads',
                data: topAppsData,
                backgroundColor: [
                    'rgba(168, 85, 247, 0.85)',
                    'rgba(59, 130, 246, 0.85)',
                    'rgba(0, 229, 255, 0.85)',
                    'rgba(16, 185, 129, 0.85)',
                    'rgba(245, 158, 11, 0.85)'
                ],
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#fff',
                    bodyColor: '#cbd5e1',
                    borderColor: '#334155',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
</script>
@endsection

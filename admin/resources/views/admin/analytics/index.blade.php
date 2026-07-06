@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-white mb-2">Analytics & Tracking</h2>
    <p class="text-gray-400">Monitor your app downloads, views, and overall performance.</p>
</div>

<!-- Key Metrics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="glass-panel p-6 rounded-xl border border-gray-800">
        <h3 class="text-gray-400 font-medium mb-1">Total Downloads (All Time)</h3>
        <div class="text-3xl font-bold text-white">{{ $totalDownloads }}</div>
    </div>
    <div class="glass-panel p-6 rounded-xl border border-gray-800">
        <h3 class="text-gray-400 font-medium mb-1">Total Views</h3>
        <div class="text-3xl font-bold text-white">{{ $totalViews }}</div>
    </div>
    <div class="glass-panel p-6 rounded-xl border border-gray-800">
        <h3 class="text-gray-400 font-medium mb-1">Most Downloaded App</h3>
        <div class="text-xl font-bold text-white mt-1">{{ $topApp ? $topApp->app_name : 'N/A' }}</div>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Chart 1: Downloads Over Time -->
    <div class="glass-panel p-6 rounded-xl border border-gray-800">
        <h3 class="text-lg font-medium text-white mb-4">Downloads Last 7 Days</h3>
        <div class="relative h-64">
            <canvas id="downloadsChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Top Apps by Downloads -->
    <div class="glass-panel p-6 rounded-xl border border-gray-800">
        <h3 class="text-lg font-medium text-white mb-4">Top 5 Apps (Downloads)</h3>
        <div class="relative h-64">
            <canvas id="topAppsChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Activity Table -->
<div class="glass-panel rounded-xl border border-gray-800 overflow-hidden">
    <div class="p-6 border-b border-gray-800">
        <h3 class="text-lg font-medium text-white">Recent Activity</h3>
    </div>
    <table class="w-full text-left">
        <thead class="bg-gray-800/50 border-b border-gray-800 text-gray-400 text-sm">
            <tr>
                <th class="px-6 py-4 font-medium">Event</th>
                <th class="px-6 py-4 font-medium">App</th>
                <th class="px-6 py-4 font-medium">IP Address</th>
                <th class="px-6 py-4 font-medium text-right">Time</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800 text-sm">
            @forelse($recentEvents as $event)
            <tr class="hover:bg-gray-800/30 transition-colors">
                <td class="px-6 py-4">
                    @if($event->event_type == 'download')
                        <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-md text-xs font-medium"><i class="fa-solid fa-download mr-1"></i> Download</span>
                    @else
                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded-md text-xs font-medium"><i class="fa-solid fa-eye mr-1"></i> View</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-white font-medium">
                    {{ $event->mobileApp ? $event->mobileApp->app_name : 'Unknown' }}
                </td>
                <td class="px-6 py-4 text-gray-400">
                    {{ $event->ip_address }}
                </td>
                <td class="px-6 py-4 text-right text-gray-500">
                    {{ $event->created_at->diffForHumans() }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                    No recent activity found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    // Prepare data from PHP backend
    const downloadsLabels = {!! json_encode($chartLabels) !!};
    const downloadsData = {!! json_encode($chartData) !!};
    
    const topAppsLabels = {!! json_encode($topAppsLabels) !!};
    const topAppsData = {!! json_encode($topAppsData) !!};

    // Chart Defaults for dark theme
    Chart.defaults.color = '#9ca3af';
    Chart.defaults.scale.grid.color = '#374151';

    // Downloads Chart (Line)
    new Chart(document.getElementById('downloadsChart'), {
        type: 'line',
        data: {
            labels: downloadsLabels,
            datasets: [{
                label: 'Downloads',
                data: downloadsData,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // Top Apps Chart (Bar)
    new Chart(document.getElementById('topAppsChart'), {
        type: 'bar',
        data: {
            labels: topAppsLabels,
            datasets: [{
                label: 'Downloads',
                data: topAppsData,
                backgroundColor: '#8b5cf6',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
@endsection

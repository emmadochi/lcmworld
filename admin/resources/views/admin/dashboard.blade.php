@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-white mb-2">Dashboard Overview</h2>
    <p class="text-gray-400">Welcome back! Here's what's happening with your apps today.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="glass-panel p-6 rounded-xl border border-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-400 font-medium">Total Apps</h3>
            <div class="w-10 h-10 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center">
                <i class="fa-solid fa-mobile-screen"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-white">{{ $totalApps ?? 0 }}</div>
        <div class="mt-2 text-sm text-green-400"><i class="fa-solid fa-arrow-up mr-1"></i> +2 this week</div>
    </div>
    
    <!-- Stat Card 2 -->
    <div class="glass-panel p-6 rounded-xl border border-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-400 font-medium">Total Downloads</h3>
            <div class="w-10 h-10 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center">
                <i class="fa-solid fa-download"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-white">{{ $totalDownloads ?? 0 }}</div>
        <div class="mt-2 text-sm text-green-400"><i class="fa-solid fa-arrow-up mr-1"></i> +12% this week</div>
    </div>

    <!-- Stat Card 3 -->
    <div class="glass-panel p-6 rounded-xl border border-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-400 font-medium">Categories</h3>
            <div class="w-10 h-10 rounded-lg bg-pink-500/20 text-pink-400 flex items-center justify-center">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-white">{{ $totalCategories ?? 0 }}</div>
    </div>
</div>
@endsection

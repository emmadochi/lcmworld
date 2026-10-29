@extends('layouts.admin')

@section('title', 'Registered Users')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-white mb-1 flex items-center">
            <i class="fa-solid fa-users text-purple-400 mr-2.5"></i> Registered Users Directory
        </h2>
        <p class="text-gray-400 text-sm">All users registered through the Lifechangers Apps mobile platform.</p>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm flex items-center justify-between">
    <div class="flex items-center">
        <i class="fa-solid fa-circle-check mr-2 text-base"></i>
        <span>{{ session('success') }}</span>
    </div>
    <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-white">&times;</button>
</div>
@endif

<!-- Quick Metrics -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="glass-panel p-5 rounded-2xl border border-gray-800 bg-slate-900/60 flex items-center justify-between">
        <div>
            <div class="text-xs uppercase tracking-wider text-gray-400 font-semibold mb-1">Total Members</div>
            <div class="text-3xl font-extrabold text-white">{{ number_format($totalUsers) }}</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-xl">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>
    <div class="glass-panel p-5 rounded-2xl border border-gray-800 bg-slate-900/60 flex items-center justify-between">
        <div>
            <div class="text-xs uppercase tracking-wider text-gray-400 font-semibold mb-1">Google Accounts</div>
            <div class="text-3xl font-extrabold text-white">{{ number_format($googleUsersCount) }}</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center text-xl">
            <i class="fa-brands fa-google"></i>
        </div>
    </div>
    <div class="glass-panel p-5 rounded-2xl border border-gray-800 bg-slate-900/60 flex items-center justify-between">
        <div>
            <div class="text-xs uppercase tracking-wider text-gray-400 font-semibold mb-1">Email / Password</div>
            <div class="text-3xl font-extrabold text-white">{{ number_format($emailUsersCount) }}</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-xl">
            <i class="fa-solid fa-envelope"></i>
        </div>
    </div>
</div>

<!-- Search & Filters -->
<div class="glass-panel p-4 rounded-2xl border border-gray-800 mb-6 bg-slate-900/40">
    <form method="GET" action="/admin/users" class="flex flex-col md:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full pl-10 pr-4 py-2.5 bg-gray-900/80 border border-gray-700/80 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors">
        </div>
        <div class="w-full md:w-56">
            <select name="provider" class="w-full px-3.5 py-2.5 bg-gray-900/80 border border-gray-700/80 rounded-xl text-sm text-white focus:outline-none focus:border-purple-500 transition-colors" onchange="this.form.submit()">
                <option value="">All Sign-In Providers</option>
                <option value="google" {{ request('provider') == 'google' ? 'selected' : '' }}>Google Sign-In</option>
                <option value="email" {{ request('provider') == 'email' ? 'selected' : '' }}>Email & Password</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-semibold transition-colors flex items-center">
                Filter
            </button>
            @if(request('search') || request('provider'))
                <a href="/admin/users" class="px-4 py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-xl text-sm font-medium transition-colors">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="glass-panel rounded-2xl border border-gray-800 overflow-hidden shadow-xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-900/60 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-800">
                <tr>
                    <th class="px-6 py-4 font-semibold">User</th>
                    <th class="px-6 py-4 font-semibold">Provider</th>
                    <th class="px-6 py-4 font-semibold">Reviews</th>
                    <th class="px-6 py-4 font-semibold">Joined Date</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/60 text-sm">
                @forelse($users as $user)
                <tr class="hover:bg-gray-800/25 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if(!empty($user->avatar))
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-purple-500/40 mr-3.5">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-purple-600 to-indigo-600 text-white font-bold text-sm flex items-center justify-center mr-3.5 shadow-md">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="text-white font-semibold text-sm">{{ $user->name }}</div>
                                <div class="text-gray-400 text-xs mt-0.5">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if(!empty($user->google_id))
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                <i class="fa-brands fa-google mr-1.5 text-xs"></i> Google
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                <i class="fa-solid fa-envelope mr-1.5 text-xs"></i> Email & Password
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium text-gray-300 bg-gray-800 border border-gray-700">
                            <i class="fa-solid fa-star text-amber-400 mr-1 text-[10px]"></i> {{ $user->reviews_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">
                        <div class="font-medium text-gray-300">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</div>
                        <div class="text-gray-500 text-[11px] mt-0.5">{{ $user->created_at ? $user->created_at->diffForHumans() : '' }}</div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to remove this user account?');" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors" title="Delete User">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <div class="w-12 h-12 rounded-full bg-gray-800/80 text-gray-500 flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-user-slash text-lg"></i>
                        </div>
                        <div class="text-white font-medium text-base mb-1">No users found</div>
                        <p class="text-xs text-gray-400">Users who register in the Lifechangers Apps mobile client will appear here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="p-4 border-t border-gray-800 bg-gray-900/30">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection

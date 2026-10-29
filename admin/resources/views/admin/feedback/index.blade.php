@extends('layouts.admin')

@section('title', 'Beta Testing Intelligence & Investor Reports')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <div class="flex items-center space-x-2 mb-1">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-400 bg-blue-500/10 border border-blue-500/20 px-2.5 py-0.5 rounded-full">
                Product Intelligence
            </span>
            <span class="text-xs text-gray-500 font-mono">Google Play 14-Day Audit</span>
        </div>
        <h2 class="text-2xl font-bold text-white">Beta Testing & Investor Due Diligence</h2>
        <p class="text-gray-400 text-sm">Elaborated bug telemetry, usability insights, and commercial monetization readiness across all LDTL apps.</p>
    </div>

    <!-- Quick Export & Report Actions for Investors -->
    <div class="flex flex-wrap items-center gap-3">
        <a href="/admin/beta-feedback/export-csv{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" 
           class="inline-flex items-center text-sm font-semibold bg-gray-800 hover:bg-gray-700 text-gray-200 border border-gray-700 px-4 py-2.5 rounded-xl transition-all shadow-md">
            <i class="fa-solid fa-file-csv mr-2 text-emerald-400"></i> Export CSV Data
        </a>

        <a href="/admin/beta-feedback/investor-report{{ request()->has('app') ? '?app=' . request('app') : '' }}" 
           class="inline-flex items-center text-sm font-semibold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-blue-600/30">
            <i class="fa-solid fa-file-signature mr-2 text-amber-300"></i>
            Executive Investor Report
            <span class="ml-2 text-[10px] uppercase font-bold bg-white/20 px-2 py-0.5 rounded-full">PDF Ready</span>
        </a>
    </div>
</div>

<!-- Key Performance Metric Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8">
    <div class="glass-panel p-6 rounded-2xl border border-gray-800 flex items-center justify-between relative overflow-hidden">
        <div>
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Total Submissions</p>
            <h3 class="text-3xl font-extrabold text-white">{{ $totalFeedback }}</h3>
            <p class="text-xs text-blue-400 mt-1 font-medium">Verified Telemetry Records</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
            <i class="fa-solid fa-comments text-xl"></i>
        </div>
    </div>

    <div class="glass-panel p-6 rounded-2xl border border-gray-800 flex items-center justify-between relative overflow-hidden">
        <div>
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Commercial Traction</p>
            <h3 class="text-3xl font-extrabold text-emerald-400">{{ $commercialConversionRate }}%</h3>
            <p class="text-xs text-emerald-400/80 mt-1 font-medium">{{ $willingToPay }} of {{ $willingToPay + $maybePay + $notWillingPay }} Ready to Pay</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
            <i class="fa-solid fa-sack-dollar text-xl"></i>
        </div>
    </div>

    <div class="glass-panel p-6 rounded-2xl border border-gray-800 flex items-center justify-between relative overflow-hidden">
        <div>
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Average CSAT Rating</p>
            <h3 class="text-3xl font-extrabold text-amber-400 flex items-center">
                {{ $avgRating }} <span class="text-sm font-normal text-gray-400 ml-1.5">/ 5.0</span>
            </h3>
            <p class="text-xs text-amber-400/80 mt-1 font-medium">
                @for($i = 0; $i < round($avgRating); $i++)
                    <i class="fa-solid fa-star text-[10px]"></i>
                @endfor
            </p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
            <i class="fa-solid fa-star text-xl"></i>
        </div>
    </div>

    <div class="glass-panel p-6 rounded-2xl border border-gray-800 flex items-center justify-between relative overflow-hidden">
        <div>
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Bug Resolution Rate</p>
            <h3 class="text-3xl font-extrabold text-purple-400">{{ $bugResolutionRate }}%</h3>
            <p class="text-xs text-purple-300 mt-1 font-medium">{{ $resolvedBugs }} of {{ $totalBugs }} Bugs Resolved</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400">
            <i class="fa-solid fa-shield-virus text-xl"></i>
        </div>
    </div>
</div>

<!-- Visual Analytics Breakdowns Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Commercial Readiness & Willingness to Pay -->
    <div class="glass-panel p-6 rounded-2xl border border-gray-800">
        <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center">
            <i class="fa-solid fa-chart-pie text-emerald-400 mr-2"></i>
            Commercial Conversion Breakdown
        </h4>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-300 font-semibold flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2"></span> Definitely Willing to Pay
                    </span>
                    <span class="text-emerald-400 font-bold">{{ $willingToPay }} ({{ $commercialConversionRate }}%)</span>
                </div>
                <div class="w-full bg-gray-800 rounded-full h-2 overflow-hidden">
                    <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $commercialConversionRate }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-300 font-semibold flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 mr-2"></span> Conditional / Maybe
                    </span>
                    <span class="text-amber-400 font-bold">
                        {{ $maybePay }} ({{ round(($maybePay / max(1, $willingToPay + $maybePay + $notWillingPay)) * 100, 1) }}%)
                    </span>
                </div>
                <div class="w-full bg-gray-800 rounded-full h-2 overflow-hidden">
                    <div class="bg-amber-500 h-2 rounded-full" style="width: {{ round(($maybePay / max(1, $willingToPay + $maybePay + $notWillingPay)) * 100, 1) }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-300 font-semibold flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-600 mr-2"></span> Free Baseline Tier Only
                    </span>
                    <span class="text-gray-400 font-bold">
                        {{ $notWillingPay }} ({{ round(($notWillingPay / max(1, $willingToPay + $maybePay + $notWillingPay)) * 100, 1) }}%)
                    </span>
                </div>
                <div class="w-full bg-gray-800 rounded-full h-2 overflow-hidden">
                    <div class="bg-gray-600 h-2 rounded-full" style="width: {{ round(($notWillingPay / max(1, $willingToPay + $maybePay + $notWillingPay)) * 100, 1) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Distribution -->
    <div class="glass-panel p-6 rounded-2xl border border-gray-800">
        <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center">
            <i class="fa-solid fa-star-half-stroke text-amber-400 mr-2"></i>
            User Satisfaction Distribution
        </h4>
        <div class="space-y-2">
            @for($star = 5; $star >= 1; $star--)
                @php
                    $count = $ratingCounts[$star] ?? 0;
                    $pct = $totalFeedback > 0 ? round(($count / $totalFeedback) * 100) : 0;
                @endphp
                <div class="flex items-center text-xs">
                    <span class="w-12 text-gray-300 font-medium flex items-center">
                        {{ $star }} <i class="fa-solid fa-star text-amber-400 text-[10px] ml-1"></i>
                    </span>
                    <div class="flex-1 mx-3 bg-gray-800 rounded-full h-2 overflow-hidden">
                        <div class="bg-amber-400 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="w-10 text-right text-gray-400 font-mono">{{ $count }}</span>
                </div>
            @endfor
        </div>
    </div>

    <!-- Telemetry Categories & Top Devices -->
    <div class="glass-panel p-6 rounded-2xl border border-gray-800 flex flex-col justify-between">
        <div>
            <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3 flex items-center">
                <i class="fa-solid fa-layer-group text-blue-400 mr-2"></i>
                Category Breakdown
            </h4>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="p-2.5 bg-gray-900/60 rounded-lg border border-gray-800">
                    <span class="text-emerald-400 font-bold block">{{ $typeBreakdown['commercial'] ?? 0 }}</span>
                    <span class="text-gray-400">Commercial / Pricing</span>
                </div>
                <div class="p-2.5 bg-gray-900/60 rounded-lg border border-gray-800">
                    <span class="text-blue-400 font-bold block">{{ $typeBreakdown['feedback'] ?? 0 }}</span>
                    <span class="text-gray-400">Usability Feedback</span>
                </div>
                <div class="p-2.5 bg-gray-900/60 rounded-lg border border-gray-800">
                    <span class="text-purple-400 font-bold block">{{ $typeBreakdown['feature_request'] ?? 0 }}</span>
                    <span class="text-gray-400">Feature Requests</span>
                </div>
                <div class="p-2.5 bg-gray-900/60 rounded-lg border border-gray-800">
                    <span class="text-red-400 font-bold block">{{ $typeBreakdown['bug'] ?? 0 }}</span>
                    <span class="text-gray-400">Reported Bugs</span>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-t border-gray-800 flex items-center justify-between text-xs text-gray-400">
            <span>Hardware Verification:</span>
            <span class="text-white font-semibold">{{ count($topDevices) }} device models active</span>
        </div>
    </div>
</div>

<!-- Filters Bar -->
<div class="glass-panel p-5 rounded-2xl border border-gray-800 mb-6">
    <form method="GET" action="/admin/beta-feedback" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Application</label>
            <select name="app" onchange="this.form.submit()" class="w-full bg-gray-900 border border-gray-700 text-gray-200 text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-blue-500">
                <option value="">All Applications</option>
                @foreach($apps as $app)
                    <option value="{{ $app->package_name }}" {{ request('app') == $app->package_name ? 'selected' : '' }}>
                        {{ $app->app_name ?: $app->package_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Category</label>
            <select name="type" onchange="this.form.submit()" class="w-full bg-gray-900 border border-gray-700 text-gray-200 text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-blue-500">
                <option value="">All Categories</option>
                <option value="commercial" {{ request('type') == 'commercial' ? 'selected' : '' }}>Commercial / Pricing</option>
                <option value="feedback" {{ request('type') == 'feedback' ? 'selected' : '' }}>General Feedback</option>
                <option value="feature_request" {{ request('type') == 'feature_request' ? 'selected' : '' }}>Feature Request</option>
                <option value="bug" {{ request('type') == 'bug' ? 'selected' : '' }}>Bug Report</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Resolution Status</label>
            <select name="status" onchange="this.form.submit()" class="w-full bg-gray-900 border border-gray-700 text-gray-200 text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-blue-500">
                <option value="">All Statuses</option>
                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                <option value="investigating" {{ request('status') == 'investigating' ? 'selected' : '' }}>Investigating</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Commercial Intent</label>
            <select name="would_pay" onchange="this.form.submit()" class="w-full bg-gray-900 border border-gray-700 text-gray-200 text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-blue-500">
                <option value="">All Intent Levels</option>
                <option value="yes" {{ request('would_pay') == 'yes' ? 'selected' : '' }}>Willing to Pay (Yes)</option>
                <option value="maybe" {{ request('would_pay') == 'maybe' ? 'selected' : '' }}>Interested (Maybe)</option>
                <option value="no" {{ request('would_pay') == 'no' ? 'selected' : '' }}>Free Tier (No)</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Search Keyword</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tester, device, note..." class="w-full bg-gray-900 border border-gray-700 text-gray-200 text-sm rounded-xl pl-3 pr-8 py-2 focus:outline-none focus:border-blue-500">
                <button type="submit" class="absolute right-2.5 top-2.5 text-gray-400 hover:text-white">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </div>
        </div>

        @if(request()->hasAny(['app', 'type', 'status', 'would_pay', 'search']))
            <div class="col-span-full pt-2 flex items-center justify-between border-t border-gray-800">
                <span class="text-xs text-gray-400">Filtering active parameters</span>
                <a href="/admin/beta-feedback" class="text-xs text-blue-400 hover:text-blue-300 font-semibold underline">
                    <i class="fa-solid fa-xmark mr-1"></i> Reset Filters
                </a>
            </div>
        @endif
    </form>
</div>

<!-- Elaborated Feedback Table -->
<div class="glass-panel rounded-2xl border border-gray-800 overflow-x-auto shadow-2xl">
    <table class="w-full text-left min-w-[760px]">
        <thead class="bg-gray-800/60 border-b border-gray-800 text-gray-400 text-xs font-semibold uppercase tracking-wider">
            <tr>
                <th class="px-6 py-4">App & Version</th>
                <th class="px-6 py-4">Category & Rating</th>
                <th class="px-6 py-4">Elaborated Feedback & Device</th>
                <th class="px-6 py-4">Commercial Intent</th>
                <th class="px-6 py-4">Status & Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800/80 text-sm">
            @forelse($feedbacks as $item)
            <tr class="hover:bg-gray-800/40 transition-colors">
                <td class="px-6 py-4">
                    <div class="font-bold text-white">{{ $item->app_name ?: $item->package_name }}</div>
                    <div class="text-xs text-blue-400 font-mono">{{ $item->package_name }}</div>
                    <span class="inline-block mt-1.5 px-2 py-0.5 bg-gray-800 text-gray-300 text-xs rounded-md border border-gray-700">
                        v{{ $item->app_version ?: '1.0' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    @if($item->type === 'bug')
                        <span class="px-2.5 py-1 bg-red-950/70 border border-red-800/80 text-red-300 text-xs rounded-full font-semibold inline-flex items-center">
                            <i class="fa-solid fa-bug mr-1"></i> Bug Report
                        </span>
                    @elseif($item->type === 'commercial')
                        <span class="px-2.5 py-1 bg-emerald-950/70 border border-emerald-800/80 text-emerald-300 text-xs rounded-full font-semibold inline-flex items-center">
                            <i class="fa-solid fa-sack-dollar mr-1"></i> Commercial / Pricing
                        </span>
                    @elseif($item->type === 'feature_request')
                        <span class="px-2.5 py-1 bg-purple-950/70 border border-purple-800/80 text-purple-300 text-xs rounded-full font-semibold inline-flex items-center">
                            <i class="fa-solid fa-wand-magic-sparkles mr-1"></i> Feature Request
                        </span>
                    @else
                        <span class="px-2.5 py-1 bg-blue-950/70 border border-blue-800/80 text-blue-300 text-xs rounded-full font-semibold inline-flex items-center">
                            <i class="fa-solid fa-comment-dots mr-1"></i> Usability Feedback
                        </span>
                    @endif
                    @if($item->rating)
                        <div class="text-amber-400 text-xs mt-2 font-bold flex items-center">
                            @for($i = 0; $i < $item->rating; $i++)
                                <i class="fa-solid fa-star text-[10px] mr-0.5"></i>
                            @endfor
                            <span class="ml-1 text-slate-300">{{ $item->rating }}/5</span>
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 max-w-lg">
                    <p class="text-gray-100 text-sm leading-relaxed">{{ $item->message }}</p>

                    @if($item->admin_notes)
                        <div class="mt-2.5 p-2 bg-blue-950/30 border border-blue-800/40 rounded-lg text-xs text-blue-300">
                            <strong><i class="fa-solid fa-wrench mr-1"></i> Resolution Note:</strong> {{ $item->admin_notes }}
                        </div>
                    @endif

                    <div class="mt-2.5 text-xs text-gray-400 flex flex-wrap gap-2">
                        @if($item->tester_name || $item->tester_email)
                            <span class="bg-gray-800/80 px-2 py-0.5 rounded-md border border-gray-700/60 text-gray-300">
                                <i class="fa-solid fa-user mr-1 text-blue-400"></i> {{ $item->tester_name ?: 'Beta Tester' }}
                                @if($item->tester_email) ({{ $item->tester_email }}) @endif
                            </span>
                        @endif
                        @if($item->device_model)
                            <span class="bg-gray-800/80 px-2 py-0.5 rounded-md border border-gray-700/60">
                                <i class="fa-solid fa-mobile-screen mr-1 text-gray-400"></i> {{ $item->device_model }}
                            </span>
                        @endif
                        @if($item->os_version)
                            <span class="bg-gray-800/80 px-2 py-0.5 rounded-md border border-gray-700/60">
                                <i class="fa-brands fa-android mr-1 text-emerald-400"></i> {{ $item->os_version }}
                            </span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($item->would_pay === 'yes')
                        <span class="px-3 py-1 bg-emerald-900/60 border border-emerald-700 text-emerald-300 rounded-lg font-bold text-xs inline-flex items-center shadow-sm">
                            <i class="fa-solid fa-circle-check mr-1.5"></i> Willing to Pay
                        </span>
                    @elseif($item->would_pay === 'maybe')
                        <span class="px-3 py-1 bg-amber-900/60 border border-amber-700 text-amber-300 rounded-lg text-xs font-semibold inline-flex items-center">
                            <i class="fa-solid fa-circle-question mr-1.5"></i> Maybe / Conditional
                        </span>
                    @elseif($item->would_pay === 'no')
                        <span class="px-3 py-1 bg-gray-800 border border-gray-700 text-gray-400 rounded-lg text-xs">
                            Free Tier Only
                        </span>
                    @else
                        <span class="text-gray-500 text-xs">Not specified</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm">
                    <form method="POST" action="/admin/beta-feedback/{{ $item->id }}/status" class="flex items-center gap-2">
                        @csrf
                        <select name="status" onchange="this.form.submit()" class="bg-gray-900 border border-gray-700 text-xs rounded-lg px-2.5 py-1.5 text-gray-200 focus:outline-none focus:border-blue-500 font-medium">
                            <option value="new" {{ $item->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="investigating" {{ $item->status === 'investigating' ? 'selected' : '' }}>Investigating</option>
                            <option value="resolved" {{ $item->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $item->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </form>
                    <div class="text-gray-500 text-xs mt-1.5">
                        {{ $item->created_at->diffForHumans() }}
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                    No matching beta feedback records found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $feedbacks->links() }}
</div>
@endsection

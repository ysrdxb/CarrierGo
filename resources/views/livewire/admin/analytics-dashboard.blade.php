<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                <p class="text-gray-600 mt-1">System-wide metrics and insights</p>
            </div>

            <!-- Date Range Selector -->
            <div class="flex space-x-2">
                @foreach(['7' => 'Last 7 days', '30' => 'Last 30 days', '90' => 'Last 90 days'] as $days => $label)
                    <button
                        wire:click="updateDateRange({{ $days }})"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition
                            {{ $dateRange == $days ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Tenants -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-gray-600 text-sm font-medium">Total Tenants</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $analytics['total_tenants'] ?? 0 }}</p>
                    </div>
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-blue-600 text-xl">👥</span>
                    </div>
                </div>
            </div>

            <!-- Active Tenants -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-gray-600 text-sm font-medium">Active</p>
                        <p class="text-3xl font-bold text-green-600">{{ $analytics['active_tenants'] ?? 0 }}</p>
                    </div>
                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="text-green-600 text-xl">✓</span>
                    </div>
                </div>
            </div>

            <!-- MRR -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-gray-600 text-sm font-medium">Monthly Revenue</p>
                        <p class="text-3xl font-bold text-green-600">${{ number_format($analytics['mrr'] ?? 0) }}</p>
                    </div>
                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="text-green-600 text-xl">💰</span>
                    </div>
                </div>
            </div>

            <!-- Churn Rate -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-gray-600 text-sm font-medium">Churn Rate</p>
                        <p class="text-3xl font-bold text-red-600">{{ $analytics['churn_rate'] ?? 0 }}%</p>
                    </div>
                    <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <span class="text-red-600 text-xl">⚠️</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Suspended -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Suspended</h3>
                <p class="text-4xl font-bold text-yellow-600">{{ $analytics['suspended_tenants'] ?? 0 }}</p>
            </div>

            <!-- Cancelled -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Cancelled</h3>
                <p class="text-4xl font-bold text-red-600">{{ $analytics['cancelled_tenants'] ?? 0 }}</p>
            </div>

            <!-- ARR -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Annual Revenue</h3>
                <p class="text-4xl font-bold text-green-600">${{ number_format($analytics['arr'] ?? 0) }}</p>
            </div>
        </div>

        <!-- Plan Distribution -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Plan Breakdown -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Plan Distribution</h3>
                <div class="space-y-3">
                    @php
                        $plans = [
                            'free' => ['color' => 'gray', 'icon' => '📋'],
                            'starter' => ['color' => 'green', 'icon' => '🚀'],
                            'professional' => ['color' => 'blue', 'icon' => '⭐'],
                            'enterprise' => ['color' => 'purple', 'icon' => '🏢'],
                        ];
                    @endphp

                    @foreach($plans as $plan => $info)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="mr-2">{{ $info['icon'] }}</span>
                                <span class="text-gray-700 font-medium capitalize">{{ $plan }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-900 font-bold">{{ $analytics['plan_distribution'][$plan] ?? 0 }}</span>
                                <div class="ml-3 w-24 bg-gray-200 rounded-full h-2">
                                    @php
                                        $percentage = $analytics['total_tenants'] > 0 ? (($analytics['plan_distribution'][$plan] ?? 0) / $analytics['total_tenants']) * 100 : 0;
                                    @endphp
                                    <div class="h-2 rounded-full
                                        @if($plan === 'enterprise')
                                            bg-purple-500
                                        @elseif($plan === 'professional')
                                            bg-blue-500
                                        @elseif($plan === 'starter')
                                            bg-green-500
                                        @else
                                            bg-gray-500
                                        @endif
                                    " style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-gray-600 text-sm ml-2">{{ round($percentage) }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Status Breakdown -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Breakdown</h3>
                <div class="space-y-4">
                    <!-- Active -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-700">Active</span>
                            <span class="text-green-600 font-bold">{{ count($analytics['status_breakdown']['active'] ?? []) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            @php
                                $activePercentage = $analytics['total_tenants'] > 0 ? (count($analytics['status_breakdown']['active'] ?? []) / $analytics['total_tenants']) * 100 : 0;
                            @endphp
                            <div class="bg-green-500 h-3 rounded-full" style="width: {{ $activePercentage }}%"></div>
                        </div>
                    </div>

                    <!-- Suspended -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-700">Suspended</span>
                            <span class="text-yellow-600 font-bold">{{ count($analytics['status_breakdown']['suspended'] ?? []) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            @php
                                $suspendedPercentage = $analytics['total_tenants'] > 0 ? (count($analytics['status_breakdown']['suspended'] ?? []) / $analytics['total_tenants']) * 100 : 0;
                            @endphp
                            <div class="bg-yellow-500 h-3 rounded-full" style="width: {{ $suspendedPercentage }}%"></div>
                        </div>
                    </div>

                    <!-- Cancelled -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-700">Cancelled</span>
                            <span class="text-red-600 font-bold">{{ count($analytics['status_breakdown']['cancelled'] ?? []) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            @php
                                $cancelledPercentage = $analytics['total_tenants'] > 0 ? (count($analytics['status_breakdown']['cancelled'] ?? []) / $analytics['total_tenants']) * 100 : 0;
                            @endphp
                            <div class="bg-red-500 h-3 rounded-full" style="width: {{ $cancelledPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if(count($analytics['expiring_soon'] ?? []) > 0 || count($analytics['expired'] ?? []) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Expiring Soon -->
                @if(count($analytics['expiring_soon'] ?? []) > 0)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-yellow-900 mb-4">⚠️ Expiring Soon</h3>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($analytics['expiring_soon'] as $tenant)
                                <div class="flex justify-between items-center p-2 bg-white rounded">
                                    <div>
                                        <p class="text-yellow-900 font-medium">{{ $tenant->name }}</p>
                                        <p class="text-sm text-yellow-700">{{ $tenant->subscription_expires_at?->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Expired -->
                @if(count($analytics['expired'] ?? []) > 0)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-red-900 mb-4">🚨 Expired Subscriptions</h3>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($analytics['expired'] as $tenant)
                                <div class="flex justify-between items-center p-2 bg-white rounded">
                                    <div>
                                        <p class="text-red-900 font-medium">{{ $tenant->name }}</p>
                                        <p class="text-sm text-red-700">Expired {{ $tenant->subscription_expires_at?->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Subscription Management</h1>
            <p class="text-gray-600 mt-1">Manage tenant subscriptions and billing</p>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Search tenants..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <!-- Plan Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Plan</label>
                    <select
                        wire:model.live="filterPlan"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="all">All Plans</option>
                        <option value="free">Free</option>
                        <option value="starter">Starter</option>
                        <option value="professional">Professional</option>
                        <option value="enterprise">Enterprise</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select
                        wire:model.live="filterStatus"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="all">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Subscriptions Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tenant</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Plan</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Expires</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($tenants as $tenant)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $tenant->name }}</div>
                                <div class="text-xs text-gray-500">{{ $tenant->domain }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($tenant->subscription_plan === 'enterprise')
                                        bg-purple-100 text-purple-800
                                    @elseif($tenant->subscription_plan === 'professional')
                                        bg-blue-100 text-blue-800
                                    @elseif($tenant->subscription_plan === 'starter')
                                        bg-green-100 text-green-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ ucfirst($tenant->subscription_plan ?? 'free') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($tenant->subscription_status === 'active')
                                        bg-green-100 text-green-800
                                    @elseif($tenant->subscription_status === 'suspended')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif
                                ">
                                    {{ ucfirst($tenant->subscription_status ?? 'inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($tenant->subscription_expires_at)
                                    {{ $tenant->subscription_expires_at->format('M d, Y') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button
                                    wire:click="viewDetails({{ $tenant->id }})"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                >
                                    Manage
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <p class="text-gray-500">No subscriptions found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $tenants->links() }}
            </div>
        </div>
    </div>

    <!-- Subscription Detail Modal -->
    @if($showDetail && $selectedTenant)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">{{ $selectedTenant->name }} - Subscription</h2>
                    <button
                        wire:click="closeDetail"
                        class="text-gray-400 hover:text-gray-600"
                    >
                        ✕
                    </button>
                </div>

                <!-- Content -->
                <div class="px-6 py-6">
                    <!-- Current Plan Details -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Plan</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Plan</p>
                                <p class="text-lg font-bold text-gray-900 capitalize">
                                    {{ $selectedTenant->subscription_plan ?? 'free' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Status</p>
                                <p class="text-lg font-bold">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($selectedTenant->subscription_status === 'active')
                                            bg-green-100 text-green-800
                                        @elseif($selectedTenant->subscription_status === 'suspended')
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-red-100 text-red-800
                                        @endif
                                    ">
                                        {{ ucfirst($selectedTenant->subscription_status ?? 'inactive') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Plan Features -->
                    @if(isset($planDetails[$selectedTenant->subscription_plan ?? 'free']))
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Plan Features</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600">Shipments Limit</p>
                                    <p class="text-lg font-bold text-gray-900">
                                        {{ $planDetails[$selectedTenant->subscription_plan ?? 'free']['shipments_limit'] }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600">Users Limit</p>
                                    <p class="text-lg font-bold text-gray-900">
                                        {{ $planDetails[$selectedTenant->subscription_plan ?? 'free']['users_limit'] }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg col-span-2">
                                    <p class="text-sm text-gray-600">Support</p>
                                    <p class="text-lg font-bold text-gray-900">
                                        {{ $planDetails[$selectedTenant->subscription_plan ?? 'free']['support'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Expiration -->
                    @if($selectedTenant->subscription_expires_at)
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-600">Subscription Expires</p>
                            <p class="text-lg font-bold text-blue-900">
                                {{ $selectedTenant->subscription_expires_at->format('F d, Y') }}
                            </p>
                            @if($selectedTenant->subscription_expires_at->isPast())
                                <p class="text-sm text-red-600 mt-2">Expired</p>
                            @elseif($selectedTenant->subscription_expires_at->diffInDays(now()) < 30)
                                <p class="text-sm text-yellow-600 mt-2">
                                    Expires in {{ $selectedTenant->subscription_expires_at->diffInDays(now()) }} days
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 border-t border-gray-200 flex justify-between space-x-3">
                    <div class="flex space-x-2">
                        @if($selectedTenant->subscription_status === 'cancelled')
                            <button
                                wire:click="renewSubscription"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium"
                            >
                                Renew Subscription
                            </button>
                        @endif

                        @if($selectedTenant->subscription_status === 'active')
                            <button
                                wire:click="openUpgradeModal"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium"
                            >
                                Change Plan
                            </button>
                        @endif

                        @if($selectedTenant->subscription_status === 'active')
                            <button
                                wire:click="cancelSubscription"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium"
                            >
                                Cancel Subscription
                            </button>
                        @endif
                    </div>

                    <button
                        wire:click="closeDetail"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-medium"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Plan Change Modal -->
    @if($showUpgradeModal && $selectedTenant)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Change Subscription Plan</h2>
                </div>

                <div class="px-6 py-4">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Select New Plan</label>

                    <div class="space-y-3">
                        @foreach(['free', 'starter', 'professional', 'enterprise'] as $plan)
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50
                                {{ $newPlan === $plan ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                <input
                                    type="radio"
                                    wire:model="newPlan"
                                    value="{{ $plan }}"
                                    class="h-4 w-4 text-blue-600"
                                >
                                <div class="ml-3">
                                    <p class="font-medium text-gray-900">{{ ucfirst($plan) }}</p>
                                    <p class="text-sm text-gray-600">
                                        @if($plan === 'free')
                                            Free
                                        @elseif($plan === 'starter')
                                            $99/month
                                        @elseif($plan === 'professional')
                                            $299/month
                                        @else
                                            $999/month
                                        @endif
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button
                        wire:click="closeUpgradeModal"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="changePlan"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                    >
                        Confirm Change
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

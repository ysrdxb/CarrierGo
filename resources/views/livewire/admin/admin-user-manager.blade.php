<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Admin Users</h1>
                <p class="text-gray-600 mt-1">Manage system administrators and their permissions</p>
            </div>
            <button
                wire:click="createAdminUser"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition"
            >
                + Add Admin User
            </button>
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Search by name or email..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Role</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Created</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $user->firstname }} {{ $user->lastname }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ optional($user->roles->first())->name ?? 'No Role' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $user->created_at?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button
                                    wire:click="editAdminUser({{ $user->id }})"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium mr-4"
                                >
                                    Edit
                                </button>
                                <button
                                    wire:click="confirmDeleteUser({{ $user->id }})"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <p class="text-gray-500">No admin users found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">
                        {{ $form['id'] ? 'Edit Admin User' : 'Create New Admin User' }}
                    </h2>
                </div>

                <div class="px-6 py-4">
                    <!-- First Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input
                            type="text"
                            wire:model="form.firstname"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="First name"
                        >
                        @error('form.firstname')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input
                            type="text"
                            wire:model="form.lastname"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Last name"
                        >
                        @error('form.lastname')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input
                            type="email"
                            wire:model="form.email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Email address"
                        >
                        @error('form.email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password {{ $form['id'] ? '(Leave empty to keep current)' : '(Required)' }}
                        </label>
                        <input
                            type="password"
                            wire:model="form.password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Password"
                        >
                        @error('form.password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    @if($form['password'])
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input
                                type="password"
                                wire:model="form.password_confirm"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Confirm password"
                            >
                            @error('form.password_confirm')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <!-- Role -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select
                            wire:model="form.role"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">Select a role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('form.role')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button
                        wire:click="cancelEdit"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="saveAdminUser"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                    >
                        Save User
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirm && $userToDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Delete Admin User</h2>
                </div>

                <div class="px-6 py-4">
                    <p class="text-gray-600">
                        Are you sure you want to delete <strong>{{ $userToDelete->firstname }} {{ $userToDelete->lastname }}</strong>?
                        This action cannot be undone.
                    </p>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button
                        wire:click="$set('showDeleteConfirm', false)"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="deleteUser"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                    >
                        Delete User
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Admin Panel — User Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-brand-50 text-brand-700 rounded-lg border border-brand-100 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-slate-500">Activate new user accounts so they can log in.</p>
                <a href="{{ route('admin.create') }}" class="inline-flex items-center bg-brand-500 text-white px-4 py-2 rounded-lg hover:bg-brand-600 transition text-sm font-medium">
                    + Add New User
                </a>
            </div>

            <div class="bg-white overflow-hidden rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wide">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4 text-sm text-slate-800 font-medium">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ str_replace('_', ' ', ucfirst($user->role)) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $user->is_active ? 'bg-brand-50 text-brand-600' : 'bg-amber-50 text-amber-600' }}">
                                        {{ $user->is_active ? 'Active' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-3">
                                    @if ($user->role !== 'admin')
                                        @if ($user->is_active)
                                            <form action="{{ route('admin.deactivate', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-danger-500 hover:text-danger-600 text-sm font-medium">
                                                    Deactivate
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.activate', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-brand-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-brand-600 transition font-medium">
                                                    Activate
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.users.change-password', $user) }}" class="text-accent-500 hover:text-accent-600 text-sm font-medium">
                                            Change Password
                                        </a>
                                    @else
                                        <span class="text-slate-300 text-sm">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
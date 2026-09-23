<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Add New User
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded-xl border border-slate-200">
                <form action="{{ route('admin.store') }}" method="POST">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="block w-full border-slate-200 rounded-lg shadow-sm focus:border-brand-400 focus:ring-brand-400">
                        @error('name')
                            <p class="text-danger-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="block w-full border-slate-200 rounded-lg shadow-sm focus:border-brand-400 focus:ring-brand-400">
                        @error('email')
                            <p class="text-danger-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Role</label>
                        <select name="role" class="block w-full border-slate-200 rounded-lg shadow-sm focus:border-brand-400 focus:ring-brand-400">
                            <option value="">Select role</option>
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="project_manager" {{ old('role') === 'project_manager' ? 'selected' : '' }}>Project Manager</option>
                            <option value="backend_team" {{ old('role') === 'backend_team' ? 'selected' : '' }}>Backend Team</option>
                            <option value="frontend_team" {{ old('role') === 'frontend_team' ? 'selected' : '' }}>Frontend Team</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="text-danger-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                        <input type="password" name="password"
                            class="block w-full border-slate-200 rounded-lg shadow-sm focus:border-brand-400 focus:ring-brand-400">
                        @error('password')
                            <p class="text-danger-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                            class="block w-full border-slate-200 rounded-lg shadow-sm focus:border-brand-400 focus:ring-brand-400">
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-brand-500 text-white px-5 py-2.5 rounded-lg hover:bg-brand-600 transition text-sm font-medium shadow-sm">
                            Add User
                        </button>
                        <a href="{{ route('admin.index') }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
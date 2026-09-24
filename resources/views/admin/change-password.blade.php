<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Change Password — {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 px-4 py-3 bg-danger-50 text-danger-600 rounded-lg border border-danger-100 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white p-6 rounded-xl border border-slate-200">
                <p class="text-sm text-slate-500 mb-4">
                    Setting a new password for <span class="font-medium text-slate-700">{{ $user->email }}</span>
                </p>

                <form method="POST" action="{{ route('admin.users.update-password', $user) }}">
                    @csrf

                    <div>
                        <x-input-label for="password" :value="__('New Password')" />
                        <x-text-input id="password" class="block mt-1.5 w-full" type="password" name="password" required autofocus />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" class="block mt-1.5 w-full" type="password" name="password_confirmation" required />
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a href="{{ route('admin.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Cancel</a>
                        <x-primary-button>{{ __('Update Password') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<x-guest-layout>
    <h2 class="text-xl font-semibold text-slate-800 mb-1">Admin Login</h2>
    <p class="text-sm text-slate-500 mb-6">Restricted access — Admins only.</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('admin.login.store') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1.5 w-full"
                            type="password"
                            name="password"
                            required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-brand-500 shadow-sm focus:ring-brand-400" name="remember">
                <span class="ms-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ms-4">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                document.getElementById('email').value = '';
                document.getElementById('password').value = '';
            }
        });
    </script>
</x-guest-layout>
<nav x-data="{ open: false }" class="bg-white border-b border-slate-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-brand-500 text-white font-semibold text-sm">HD</span>
                        <span class="font-semibold text-brand-700 hidden sm:inline">Help Desk</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    @if (Auth::user()->isUser())
                        <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')">
                            My Tickets
                        </x-nav-link>
                    @elseif (Auth::user()->isProjectManager())
                        <x-nav-link :href="route('pm.index')" :active="request()->routeIs('pm.*')">
                            All Tickets
                        </x-nav-link>
                    @elseif (Auth::user()->isBackendTeam() || Auth::user()->isFrontendTeam())
                        <x-nav-link :href="route('team.index')" :active="request()->routeIs('team.*') && ! request()->routeIs('team.my-history')">
                            Assigned Tickets
                        </x-nav-link>
                        <x-nav-link :href="route('team.my-history')" :active="request()->routeIs('team.my-history')">
                            Ticket History
                        </x-nav-link>
                    @elseif (Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.*')">
                            Manage Users
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                @if (Auth::user()->isProjectManager())
                    <div x-data="{
                        open: false,
                        count: 0,
                        notifications: [],
                        loading: false,
                        poll() {
                            fetch('{{ route('pm.new-tickets') }}')
                                .then(r => r.json())
                                .then(d => this.count = d.count);
                        },
                        openDropdown() {
                            this.open = !this.open;
                            if (this.open) {
                                this.loading = true;
                                fetch('{{ route('pm.notifications') }}')
                                    .then(r => r.json())
                                    .then(d => {
                                        this.notifications = d.tickets;
                                        this.count = 0;
                                        this.loading = false;
                                    });
                            }
                        }
                    }"
                    x-init="poll(); setInterval(() => poll(), 15000); window.addEventListener('pm-new-ticket', () => poll())"
                    @click.outside="open = false"
                    class="relative">
                        <button @click="openDropdown()" class="relative p-2 text-slate-500 hover:text-brand-600 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="count > 0" x-text="count" class="absolute -top-0.5 -right-0.5 bg-danger-500 text-white text-[10px] font-semibold rounded-full h-4 w-4 flex items-center justify-center"></span>
                        </button>

                        <div x-show="open" x-transition x-cloak
                            class="absolute right-0 mt-2 w-80 bg-white rounded-xl border border-slate-200 shadow-lg overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-sm font-semibold text-slate-800">Notifications</p>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                <template x-if="loading">
                                    <p class="px-4 py-6 text-sm text-slate-400 text-center">Loading...</p>
                                </template>
                                <template x-if="!loading && notifications.length === 0">
                                    <p class="px-4 py-6 text-sm text-slate-400 text-center">No new tickets.</p>
                                </template>
                                <template x-for="n in notifications" :key="n.reference_number">
                                    <a href="{{ route('pm.index') }}" class="block px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0">
                                        <p class="text-sm font-medium text-slate-800" x-text="n.title"></p>
                                        <p class="text-xs text-slate-400 mt-0.5">
                                            <span x-text="n.reference_number" class="font-mono"></span> · <span x-text="n.created_by"></span> · <span x-text="n.time"></span>
                                        </p>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>
                @elseif (Auth::user()->isAdmin())
                    <div x-data="{
                        open: false,
                        count: 0,
                        notifications: [],
                        loading: false,
                        poll() {
                            fetch('{{ route('admin.password-requests.new') }}')
                                .then(r => r.json())
                                .then(d => this.count = d.count);
                        },
                        openDropdown() {
                            this.open = !this.open;
                            if (this.open) {
                                this.loading = true;
                                fetch('{{ route('admin.password-requests') }}')
                                    .then(r => r.json())
                                    .then(d => {
                                        this.notifications = d.requests;
                                        this.count = 0;
                                        this.loading = false;
                                    });
                            }
                        }
                    }"
                    x-init="poll(); setInterval(() => poll(), 15000)"
                    @click.outside="open = false"
                    class="relative">
                        <button @click="openDropdown()" class="relative p-2 text-slate-500 hover:text-brand-600 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="count > 0" x-text="count" class="absolute -top-0.5 -right-0.5 bg-danger-500 text-white text-[10px] font-semibold rounded-full h-4 w-4 flex items-center justify-center"></span>
                        </button>

                        <div x-show="open" x-transition x-cloak
                            class="absolute right-0 mt-2 w-80 bg-white rounded-xl border border-slate-200 shadow-lg overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-sm font-semibold text-slate-800">Password Reset Requests</p>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                <template x-if="loading">
                                    <p class="px-4 py-6 text-sm text-slate-400 text-center">Loading...</p>
                                </template>
                                <template x-if="!loading && notifications.length === 0">
                                    <p class="px-4 py-6 text-sm text-slate-400 text-center">No pending requests.</p>
                                </template>
                                <template x-for="n in notifications" :key="n.user_id">
                                    <a x-bind:href="'{{ url('/admin/users') }}/' + n.user_id + '/change-password'" class="block px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0">
                                        <p class="text-sm font-medium text-slate-800" x-text="n.name"></p>
                                        <p class="text-xs text-slate-400 mt-0.5">
                                            <span x-text="n.email"></span> · <span x-text="n.time"></span>
                                        </p>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>
                @endif

                <span class="text-xs uppercase tracking-wide text-brand-600 font-medium bg-brand-50 px-2.5 py-1 rounded-full">
                    {{ str_replace('_', ' ', Auth::user()->role) }}
                </span>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-600 bg-white hover:text-brand-600 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ Auth::user()->isAdmin() ? route('admin.logout') : route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="Auth::user()->isAdmin() ? route('admin.logout') : route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 focus:text-slate-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if (Auth::user()->isUser())
                <x-responsive-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')">
                    My Tickets
                </x-responsive-nav-link>
            @elseif (Auth::user()->isProjectManager())
                <x-responsive-nav-link :href="route('pm.index')" :active="request()->routeIs('pm.*')">
                    All Tickets
                </x-responsive-nav-link>
            @elseif (Auth::user()->isBackendTeam() || Auth::user()->isFrontendTeam())
                <x-responsive-nav-link :href="route('team.index')" :active="request()->routeIs('team.*') && ! request()->routeIs('team.my-history')">
                    Assigned Tickets
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('team.my-history')" :active="request()->routeIs('team.my-history')">
                    Ticket History
                </x-responsive-nav-link>
            @elseif (Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.*')">
                    Manage Users
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-100">
            <div class="px-4">
                <div class="font-medium text-base text-slate-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
                <div class="text-xs uppercase tracking-wide text-brand-600 font-medium mt-1">{{ str_replace('_', ' ', Auth::user()->role) }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ Auth::user()->isAdmin() ? route('admin.logout') : route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="Auth::user()->isAdmin() ? route('admin.logout') : route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
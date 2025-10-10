<nav x-data="{ open: false }" class="bg-white/80 dark:bg-gray-800/80 backdrop-blur border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-6">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10 rounded-xl shadow-sm">
                </a>

                <!-- Tabs (Desktop) -->
                <div class="hidden sm:flex items-center">
                    @php
                        $isDash = request()->routeIs('dashboard');
                        $isHist = request()->routeIs('history');
                    @endphp

                    <div class="flex items-center gap-2 bg-gray-100/70 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-xl p-1">
                        <!-- DASHBOARD -->
                        <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition
                                {{ $isDash
                                        ? 'bg-white text-indigo-700 shadow-sm dark:bg-gray-700 dark:text-indigo-200'
                                        : 'text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white' }}">
                            <svg class="h-5 w-5 {{ $isDash ? 'text-indigo-600 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-width="2" d="M3 12l9-9 9 9M4 10v10h6V14h4v6h6V10"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- HISTORIAL -->
                        <a href="{{ route('history') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition
                                {{ $isHist
                                        ? 'bg-white text-indigo-700 shadow-sm dark:bg-gray-700 dark:text-indigo-200'
                                        : 'text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white' }}">
                            <svg class="h-5 w-5 {{ $isHist ? 'text-indigo-600 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-width="2" d="M12 8v5l3 3M12 22a10 10 0 110-20 10 10 0 010 20z"/>
                            </svg>
                            <span>Historial</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Right side (desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Theme Toggle (antes del perfil) -->
                <x-theme-toggle class="me-3" />

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md
                                       text-gray-600 dark:text-gray-300 bg-white/60 dark:bg-gray-800/60 hover:text-gray-900 dark:hover:text-white
                                       focus:outline-none transition">
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

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 dark:text-gray-400
                               hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': ! open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <!-- icon + label -->
                <span class="inline-flex items-center gap-2">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="M3 12l9-9 9 9M4 10v10h6V14h4v6h6V10"/></svg>
                    Dashboard
                </span>
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('history')" :active="request()->routeIs('history') || request()->routeIs('history.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="M12 8v5l3 3M12 22a10 10 0 110-20 10 10 0 010 20z"/></svg>
                    Historial
                </span>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            <div class="px-4 flex items-center justify-between">
                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <x-theme-toggle />
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
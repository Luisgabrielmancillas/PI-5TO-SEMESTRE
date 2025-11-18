<nav x-data="{ open: false }"
     class="bg-white/80 dark:bg-gray-800/80 backdrop-blur border-b border-gray-200 dark:border-gray-700 transition-colors duration-200 relative z-50">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-6">
                <!-- Logo -->
                <a href="{{ auth()->check() ? route('dashboard') : route('landing') }}" class="shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10 rounded-xl shadow-sm">
                </a>

                <!-- Tabs (Desktop) -->
                @auth
                <div class="hidden sm:flex items-center">
                    @php
                        $isDash = request()->routeIs('dashboard');
                        $isComp = request()->routeIs('comparacion');
                        $isHort = request()->routeIs('hortalizas');
                        $isHist = request()->routeIs('history') || request()->routeIs('history.*');
                        $isGest = request()->routeIs('gestion.index');
                    @endphp

                    <div class="flex items-center gap-2 bg-gray-100/70 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-xl p-1">
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition {{ $isDash ? 'bg-white text-indigo-700 shadow-sm dark:bg-gray-700 dark:text-indigo-200' : 'text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white' }}">
                            <svg class="h-5 w-5 {{ $isDash ? 'text-indigo-600 dark:text-indigo-300' : 'text-gray-400' }}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" d="M3 12l9-9 9 9M4 10v10h6V14h4v6h6V10"/>
                            </svg>
                            <span>Resumen</span>
                        </a>

                        <!-- Comparación -->
                        <a href="{{ route('comparacion') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition
                                {{ $isComp
                                    ? 'bg-white text-purple-700 shadow-sm dark:bg-gray-700 dark:text-purple-200'
                                    : 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }}">
                            <svg class="h-5 w-5 {{ $isComp ? 'text-purple-600 dark:text-purple-300' : 'text-gray-400 dark:text-gray-400' }}" 
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 20h18"/>
                                <path d="M5 20V12h3v8z"/>
                                <path d="M16 20V8h3v12z"/>
                                <path d="M8 4h8"/>
                                <path d="M10 2l-2 2 2 2"/>
                                <path d="M14 2l2 2-2 2"/>
                            </svg>
                            <span>Comparación</span>
                        </a>
                        
                        <!-- HORTALIZAS -->
                        <a href="{{ route('hortalizas') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition
                                {{ $isHort
                                    ? 'bg-white text-green-700 shadow-sm dark:bg-gray-700 dark:text-green-200'
                                    : 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }}">
                            <svg class="h-5 w-5 {{ $isHort ? 'text-green-600 dark:text-green-300' : 'text-gray-400 dark:text-gray-400' }}" 
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20v-7"/>
                                <path d="M12 13C12 7 6 5 6 5s-1 6 6 8"/>
                                <path d="M12 13c0-6 6-8 6-8s1 6-6 8"/>
                                <path d="M3 20h18"/>
                            </svg>
                            <span>Hortalizas</span>
                        </a>

                        <a href="{{ route('history') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition {{ $isHist ? 'bg-white text-pink-700 shadow-sm dark:bg-gray-700 dark:text-pink-200' : 'text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white' }}">
                            <span>Historial</span>
                        </a>

                        <!-- GESTIÓN DE USUARIOS -->
                        <a href="{{ route('gestion.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition
                                  {{ $isGest
                                      ? 'bg-white text-amber-700 shadow-sm dark:bg-gray-700 dark:text-amber-200'
                                      : 'text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white' }}">
                            <svg class="h-5 w-5 {{ $isGest ? 'text-amber-600 dark:text-amber-300' : 'text-gray-400 dark:text-gray-400' }}" 
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="7" r="3"/>
                                <path d="M4 20v-2a5 5 0 0 1 5-5h0"/>
                                <circle cx="18" cy="13" r="2"/>
                                <path d="M18 9v2M18 15v2M14 13h2M20 13h2M16.6 10.6l1.4 1.4M16.6 15.4l1.4-1.4M19.4 12l1.4-1.4M19.4 14l1.4 1.4"/>
                            </svg>
                            <span>Gestión Usuarios</span>
                        </a>
                    </div> <!-- Cierre de tabs -->
                </div> <!-- Cierre sm:flex -->
                @endauth
            </div>

            

            <!-- Right side (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:space-x-3 relative">
                @auth
                <!-- 🌐 Selector de idioma (escritorio) -->
                <div class="relative">
                    <button id="btn-traducir"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        🌐 <span id="btn-traducir-text" class="text-sm">Idioma</span>
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Menú de idiomas (escritorio) -->
                    <div id="menu-idiomas"
                         class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-2 hidden">
                        <button data-lang="es"
                                class="block w-full text-left px-3 py-1.5 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                            Español 🇪🇸
                        </button>
                        <button data-lang="en"
                                class="block w-full text-left px-3 py-1.5 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                            English 🇬🇧
                        </button>
                        <button data-lang="fr"
                                class="block w-full text-left px-3 py-1.5 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                            Français 🇫🇷
                        </button>
                        <button data-lang="de"
                                class="block w-full text-left px-3 py-1.5 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                            Deutsch 🇩🇪
                        </button>
                    </div>
                </div>

                <!-- 🔔 Botón de notificaciones -->
                <div class="relative" id="noti-container">
                    <button id="btn-notificaciones"
                            class="relative flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700
                                   text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i class="ri-notification-3-line text-lg"></i>
                        <span id="noti-count"
                              class="hidden absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">0</span>
                    </button>
                
                    <!-- Menú desplegable de notificaciones -->
                    <div id="noti-menu"
                         class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-2 z-50">
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-2">No hay notificaciones</p>
                    </div>
                </div>


                <x-theme-toggle />
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 dark:text-gray-300 bg-white/60 dark:bg-gray-800/60 hover:text-gray-900 dark:hover:text-white focus:outline-none transition">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Perfil</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();">
                                Cerrar sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @endauth
            </div>

            <!-- Botón menú móvil -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                              class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                              class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú móvil -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium 
               {{ request()->routeIs('dashboard') ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-gray-700 dark:text-indigo-200' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                Resumen
            </a>
            <a href="{{ route('comparacion') }}"
               class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium 
               {{ request()->routeIs('comparacion') ? 'bg-purple-50 border-purple-500 text-purple-700 dark:bg-gray-700 dark:text-purple-200' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                Comparación
            </a>
            <a href="{{ route('hortalizas') }}"
            class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium 
            {{ request()->routeIs('hortalizas') ? 'bg-green-50 border-green-500 text-green-700 dark:bg-gray-700 dark:text-green-200' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                Hortalizas
            </a>
            <a href="{{ route('history') }}"
               class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium 
               {{ request()->routeIs('history') ? 'bg-pink-50 border-pink-500 text-pink-700 dark:bg-gray-700 dark:text-pink-200' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                Historial
            </a>
            <a href="{{ route('gestion.index') }}"
               class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium 
               {{ request()->routeIs('gestion.index') ? 'bg-amber-50 border-amber-500 text-amber-700 dark:bg-gray-700 dark:text-amber-200' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                Gestión Usuarios
            </a>
        </div>

        <!-- Selector de idioma en móvil -->
        <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="text-sm font-medium text-gray-700 dark:text-gray-200">Idioma</div>
                <div class="flex items-center gap-2">
                    <button data-lang="es" class="px-2 py-1 rounded-md text-sm hover:bg-gray-100 dark:hover:bg-gray-700">ES 🇪🇸</button>
                    <button data-lang="en" class="px-2 py-1 rounded-md text-sm hover:bg-gray-100 dark:hover:bg-gray-700">EN 🇬🇧</button>
                    <button data-lang="fr" class="px-2 py-1 rounded-md text-sm hover:bg-gray-100 dark:hover:bg-gray-700">FR 🇫🇷</button>
                    <button data-lang="de" class="px-2 py-1 rounded-md text-sm hover:bg-gray-100 dark:hover:bg-gray-700">DE 🇩🇪</button>
                </div>
            </div>
        </div>

        <!-- Menú usuario en móvil -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Perfil</x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault(); this.closest('form').submit();">
                        Cerrar sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth

        <!-- Theme toggle in mobile (reuse your component) -->
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            <x-theme-toggle />
        </div>
    </div>


</nav>
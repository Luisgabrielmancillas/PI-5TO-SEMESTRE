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

                        <a href="{{ route('comparacion') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition {{ $isComp ? 'bg-white text-purple-700 shadow-sm dark:bg-gray-700 dark:text-purple-200' : 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }}">
                            <span>Comparación</span>
                        </a>

                        <a href="{{ route('hortalizas') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition {{ $isHort ? 'bg-white text-green-700 shadow-sm dark:bg-gray-700 dark:text-green-200' : 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }}">
                            <span>Hortalizas</span>
                        </a>

                        <a href="{{ route('history') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition {{ $isHist ? 'bg-white text-pink-700 shadow-sm dark:bg-gray-700 dark:text-pink-200' : 'text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white' }}">
                            <span>Historial</span>
                        </a>
                    </div>
                </div>
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
               {{ request()->routeIs('comparacion') ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-gray-700 dark:text-indigo-200' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                Comparación
            </a>
            <a href="{{ route('hortalizas') }}"
               class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium 
               {{ request()->routeIs('hortalizas') ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-gray-700 dark:text-indigo-200' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                Hortalizas
            </a>
            <a href="{{ route('history') }}"
               class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium 
               {{ request()->routeIs('history') ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-gray-700 dark:text-indigo-200' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                Historial
            </a>
        </div>

        <!-- Selector de idioma en móvil -->
        <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="text-sm font-medium text-gray-700 dark:text-gray-200">Idioma</div>
                <div class="flex items-center gap-2">
                    <!-- mobile language buttons -->
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

    <!-- Script de traducción dinámica y manejo de menús -->
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        // Elements: desktop button/menu, mobile buttons
        const boton = document.getElementById("btn-traducir");
        const botonText = document.getElementById("btn-traducir-text");
        const menu = document.getElementById("menu-idiomas");
        const storageLangKey = "idiomaActual";
        const idiomas = { es: "Español", en: "Inglés", fr: "Francés", de: "Alemán" };

        // mobile language buttons (delegation)
        const mobileLangButtonsSelector = '[data-lang]';

        // Current language
        let idiomaActual = localStorage.getItem(storageLangKey) || "es";
        actualizarBoton();

        // --- abrir/cerrar menú (desktop) ---
        if (boton) {
            boton.addEventListener("click", e => {
                e.stopPropagation();
                if (menu) menu.classList.toggle("hidden");
            });
        }

        // Close desktop menu when clicking outside
        document.addEventListener("click", e => {
            if (menu && boton && !menu.contains(e.target) && !boton.contains(e.target)) menu.classList.add("hidden");
        });

        // --- aplicar traducción guardada (si existe) ---
        const pageKey = window.location.pathname;
        if (idiomaActual !== "es") {
            aplicarTraduccionGuardada(pageKey, idiomaActual).then(aplico => {
                if (!aplico) traducirPagina(idiomaActual);
            });
        }

        // --- Desktop menu language buttons ---
        if (menu) {
            menu.querySelectorAll("button[data-lang]").forEach(btn => {
                btn.addEventListener("click", async () => {
                    const nuevo = btn.getAttribute("data-lang");
                    if (!nuevo) return;
                    if (menu) menu.classList.add("hidden");
                    if (nuevo === idiomaActual) return;
                    idiomaActual = nuevo;
                    localStorage.setItem(storageLangKey, nuevo);
                    actualizarBoton();
                    await traducirPagina(nuevo);
                });
            });
        }

        // --- Mobile language buttons (in mobile menu) ---
        document.querySelectorAll('.sm\\:hidden [data-lang]').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const nuevo = btn.getAttribute('data-lang');
                if (!nuevo) return;
                if (nuevo === idiomaActual) return;
                idiomaActual = nuevo;
                localStorage.setItem(storageLangKey, nuevo);
                actualizarBoton();
                await traducirPagina(nuevo);
            });
        });

        // --- actualizar texto del botón ---
        function actualizarBoton() {
            if (botonText) botonText.textContent = idiomas[idiomaActual] || idiomaActual.toUpperCase();
        }

        // --- aplicar traducción guardada (si hay) ---
        async function aplicarTraduccionGuardada(pagePath, lang) {
            try {
                const key = `tr_${lang}_${pagePath}`;
                const raw = localStorage.getItem(key);
                if (!raw) return false;
                const data = JSON.parse(raw);
                if (!data.traducciones) return false;
                const nodos = obtenerNodosTraducibles();
                nodos.forEach((n, i) => {
                    if (data.traducciones[i]) n.textContent = data.traducciones[i];
                });
                return true;
            } catch (e) {
                console.error("Error aplicando traducción guardada:", e);
                return false;
            }
        }

        // --- traducir página usando heurística ---
        async function traducirPagina(lang) {
            const original = botonText ? botonText.innerHTML : "";
            if (botonText) botonText.innerHTML = "⌛ Traduciendo...";
            const nodos = obtenerNodosTraducibles();
            if (nodos.length === 0) {
                if (botonText) botonText.innerHTML = original;
                return;
            }
            const textos = nodos.map(n => n.textContent.trim());
            try {
                const resp = await fetch("/traducir", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ textos, idioma: lang })
                });
                const data = await resp.json();
                if (data.traducciones) {
                    nodos.forEach((n, i) => n.textContent = data.traducciones[i] || n.textContent);
                    const pageKeyStorage = `tr_${lang}_${window.location.pathname}`;
                    localStorage.setItem(pageKeyStorage, JSON.stringify({ traducciones: data.traducciones }));
                }
            } catch (e) {
                console.error("Error al traducir página:", e);
            } finally {
                actualizarBoton();
            }
        }

        // --- obtener nodos que contienen texto real (evitar números, fechas, etc) ---
        function obtenerNodosTraducibles() {
            const nodos = [];
            const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
                acceptNode: node => {
                    if (!node.textContent) return NodeFilter.FILTER_REJECT;
                    const text = node.textContent.trim();
                    if (!text) return NodeFilter.FILTER_REJECT;
                    const parent = node.parentNode;
                    if (!parent) return NodeFilter.FILTER_REJECT;
                    const tag = parent.nodeName.toLowerCase();
                    const blacklist = ['script','style','noscript','svg','canvas','input','textarea','select','option'];
                    if (blacklist.includes(tag)) return NodeFilter.FILTER_REJECT;
                    // evitar traducir texto que sea solo números, fechas o símbolos
                    if (/^[\d\s\.:\/\-,%°]+$/.test(text)) return NodeFilter.FILTER_REJECT;
                    // traducir solo si hay letras
                    if (/[A-Za-zÀ-ÖØ-öø-ÿÑñ]/.test(text)) return NodeFilter.FILTER_ACCEPT;
                    return NodeFilter.FILTER_REJECT;
                }
            });
            while (walker.nextNode()) nodos.push(walker.currentNode);
            return nodos;
        }
    });
    </script>

</nav>

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

            <!-- Right side -->
            <div class="hidden sm:flex sm:items-center sm:space-x-3 relative">
                @auth
                <!-- 🌐 Selector de idioma -->
                <div class="relative">
                    <button id="btn-traducir"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        🌐 <span class="text-sm">Idioma</span>
                    </button>

                    <!-- Menú de idiomas (aparece debajo del botón) -->
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
        </div>
    </div>

    <!-- Script de traducción dinámica -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const boton = document.getElementById("btn-traducir");
    const menu = document.getElementById("menu-idiomas");
    const storageLangKey = "idiomaActual";
    const idiomas = { es: "Español", en: "Inglés", fr: "Francés", de: "Alemán" };

    let idiomaActual = localStorage.getItem(storageLangKey) || "es";
    actualizarBoton();

    // --- abrir/cerrar menú ---
    boton.addEventListener("click", e => {
        e.stopPropagation();
        menu.classList.toggle("hidden");
    });
    document.addEventListener("click", e => {
        if (!menu.contains(e.target) && !boton.contains(e.target)) menu.classList.add("hidden");
    });

    // --- cargar traducción guardada (si existe) ---
    const pageKey = window.location.pathname;
    if (idiomaActual !== "es") {
        aplicarTraduccionGuardada(pageKey, idiomaActual).then(aplico => {
            if (!aplico) traducirPagina(idiomaActual);
        });
    }

    // --- manejar cambio de idioma ---
    menu.querySelectorAll("button[data-lang]").forEach(btn => {
        btn.addEventListener("click", async () => {
            const nuevo = btn.getAttribute("data-lang");
            menu.classList.add("hidden");
            if (nuevo === idiomaActual) return;
            idiomaActual = nuevo;
            localStorage.setItem(storageLangKey, nuevo);
            actualizarBoton();
            await traducirPagina(nuevo);
        });
    });

    // --- actualizar texto del botón ---
    function actualizarBoton() {
        boton.innerHTML = `🌐 <span class="text-sm">${idiomas[idiomaActual] || idiomaActual.toUpperCase()}</span>`;
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
        const original = boton.innerHTML;
        boton.innerHTML = "⌛ Traduciendo...";
        const nodos = obtenerNodosTraducibles();
        if (nodos.length === 0) {
            boton.innerHTML = original;
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
                // guardar traducción en cache local
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

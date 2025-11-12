@props(['title' => 'Panel de Control'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <title>{{ $title }}</title>

        <!-- Anti-FOUC: fija el tema antes de pintar -->
        <script>
        (function () {
          try {
            const t = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (t === 'dark' || (!t && prefersDark)) {
              document.documentElement.classList.add('dark');
            } else {
              document.documentElement.classList.remove('dark');
            }
          } catch (e) {}
        })();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet"/>
        <!-- Favicons PNG -->
        <link rel="icon" href="{{ asset('favicon-32.png') }}" sizes="32x32" type="image/png">
        <link rel="icon" href="{{ asset('favicon-16.png') }}" sizes="16x16" type="image/png">

        <!-- iOS -->
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}" sizes="180x180">

        <!-- (Opcional) Compatibilidad extra -->
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-200">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow transition-colors duration-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>



<!-- Script de traducción sin toast y con indicador solo primera vez -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const storageLangKey = "idiomaActual";
    const cacheKey = "traduccionesCache";
    const seccionesTraducidasKey = "seccionesTraducidas";
    const idiomas = { 
        es: "Español", 
        en: "English", 
        fr: "Français", 
        de: "Deutsch" 
    };
    
    let idiomaActual = localStorage.getItem(storageLangKey) || "es";
    let traduccionesCache = JSON.parse(localStorage.getItem(cacheKey)) || {};
    let seccionesTraducidas = JSON.parse(localStorage.getItem(seccionesTraducidasKey)) || {};

    // Inicializar sistema inmediatamente
    initializeLanguageSystem();
    aplicarTraduccionInicial();

    function initializeLanguageSystem() {
        const boton = document.getElementById("btn-traducir");
        const menu = document.getElementById("menu-idiomas");

        if (boton && menu) {
            boton.addEventListener("click", e => {
                e.stopPropagation();
                menu.classList.toggle("hidden");
            });

            document.addEventListener("click", e => {
                if (menu && !menu.contains(e.target) && boton && !boton.contains(e.target)) {
                    menu.classList.add("hidden");
                }
            });
        }

        // Botones de idioma
        document.querySelectorAll("button[data-lang]").forEach(btn => {
            btn.addEventListener("click", async () => {
                const nuevo = btn.getAttribute("data-lang");
                if (!nuevo || nuevo === idiomaActual) return;
                
                if (menu) menu.classList.add("hidden");
                await cambiarIdioma(nuevo);
            });
        });

        // Observar cambios en la página para detectar cambios de sección
        observarCambiosPagina();
    }

    function observarCambiosPagina() {
        let ultimaURL = window.location.href;
        
        const observer = new MutationObserver((mutations) => {
            const urlActual = window.location.href;
            
            // Detectar cambio de URL (navegación SPA)
            if (urlActual !== ultimaURL) {
                ultimaURL = urlActual;
                console.log("🔄 Cambio de sección detectado:", urlActual);
                
                if (idiomaActual !== "es") {
                    manejarCambioSeccion();
                }
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['class', 'id']
        });

        // También observar cambios en history para SPAs
        if (window.history && window.history.pushState) {
            const originalPushState = history.pushState;
            const originalReplaceState = history.replaceState;
            
            history.pushState = function() {
                originalPushState.apply(this, arguments);
                setTimeout(() => {
                    if (idiomaActual !== "es") {
                        console.log("🔄 Navegación SPA (pushState) detectada");
                        manejarCambioSeccion();
                    }
                }, 50);
            };
            
            history.replaceState = function() {
                originalReplaceState.apply(this, arguments);
                setTimeout(() => {
                    if (idiomaActual !== "es") {
                        console.log("🔄 Navegación SPA (replaceState) detectada");
                        manejarCambioSeccion();
                    }
                }, 50);
            };
        }
    }

    function manejarCambioSeccion() {
        const seccionActual = obtenerIdentificadorSeccion();
        const claveSeccion = `${idiomaActual}_${seccionActual}`;
        
        // Verificar si es la primera vez en esta sección con este idioma
        if (!seccionesTraducidas[claveSeccion]) {
            console.log(`🔄 Primera vez en sección ${seccionActual} con idioma ${idiomaActual}`);
            mostrarIndicadorTraduccion();
            seccionesTraducidas[claveSeccion] = true;
            localStorage.setItem(seccionesTraducidasKey, JSON.stringify(seccionesTraducidas));
            
            // Aplicar traducción para esta sección
            aplicarTraduccionSeccion();
        } else {
            console.log(`✅ Sección ${seccionActual} ya traducida anteriormente`);
            aplicarTraduccionCache(idiomaActual);
        }
    }

    function obtenerIdentificadorSeccion() {
        // Intentar obtener identificador de la sección actual
        const path = window.location.pathname;
        const hash = window.location.hash;
        
        if (hash && hash !== '#') {
            return hash;
        }
        
        if (path && path !== '/') {
            return path.split('/').pop() || 'inicio';
        }
        
        return 'inicio';
    }

    function mostrarIndicadorTraduccion() {
        const botonText = document.getElementById("btn-traducir-text");
        if (botonText) {
            // Guardar el texto original temporalmente
            if (!botonText.dataset.originalText) {
                botonText.dataset.originalText = botonText.textContent;
            }
            
            // Mostrar "Traduciendo..." en el idioma correspondiente
            if (idiomaActual === 'es') {
                botonText.textContent = "⌛ Traduciendo...";
            } else if (idiomaActual === 'en') {
                botonText.textContent = "⌛ Translating...";
            } else if (idiomaActual === 'fr') {
                botonText.textContent = "⌛ Traduction...";
            } else if (idiomaActual === 'de') {
                botonText.textContent = "⌛ Übersetzung...";
            }
            

            setTimeout(() => {
                actualizarBotonIdioma();
            }, 1500);
        }
    }

    function aplicarTraduccionSeccion() {

        aplicarTraduccionCache(idiomaActual);
        
 
        traducirPagina(idiomaActual);
    }

    function aplicarTraduccionInicial() {
        actualizarBotonIdioma();
        
        if (idiomaActual !== "es") {
            const seccionActual = obtenerIdentificadorSeccion();
            const claveSeccion = `${idiomaActual}_${seccionActual}`;
            

            if (!seccionesTraducidas[claveSeccion]) {
                seccionesTraducidas[claveSeccion] = true;
                localStorage.setItem(seccionesTraducidasKey, JSON.stringify(seccionesTraducidas));
                mostrarIndicadorTraduccion();
            }
            

            aplicarTraduccionCache(idiomaActual);
            

            if (verificarContenidoSinTraducir()) {
                traducirPagina(idiomaActual);
            }
        }
    }

    function verificarContenidoSinTraducir() {
        const textosEs = ['Resumen', 'Historial', 'Configuración', 'Perfil', 'Salir', 'Inicio'];
        return textosEs.some(texto => 
            document.body.textContent.includes(texto) && idiomaActual !== "es"
        );
    }

    async function cambiarIdioma(nuevoIdioma) {

        resetearSeccionesParaIdioma(nuevoIdioma);
        

        const botonText = document.getElementById("btn-traducir-text");
        if (botonText) {
            if (nuevoIdioma === 'es') {
                botonText.textContent = "⌛ Traduciendo...";
            } else if (nuevoIdioma === 'en') {
                botonText.textContent = "⌛ Translating...";
            } else if (nuevoIdioma === 'fr') {
                botonText.textContent = "⌛ Traduction...";
            } else if (nuevoIdioma === 'de') {
                botonText.textContent = "⌛ Übersetzung...";
            }
        }
        
        idiomaActual = nuevoIdioma;
        localStorage.setItem(storageLangKey, nuevoIdioma);
        
        if (nuevoIdioma === "es") {
            restaurarTextoOriginal();
        } else {

            aplicarTraduccionCache(nuevoIdioma);
            await traducirPagina(nuevoIdioma);
            

            const seccionActual = obtenerIdentificadorSeccion();
            const claveSeccion = `${nuevoIdioma}_${seccionActual}`;
            seccionesTraducidas[claveSeccion] = true;
            localStorage.setItem(seccionesTraducidasKey, JSON.stringify(seccionesTraducidas));
            
            actualizarBotonIdioma();
        }
    }

    function resetearSeccionesParaIdioma(idioma) {

        Object.keys(seccionesTraducidas).forEach(clave => {
            if (clave.startsWith(idioma + '_')) {
                delete seccionesTraducidas[clave];
            }
        });
        localStorage.setItem(seccionesTraducidasKey, JSON.stringify(seccionesTraducidas));
        console.log(`🔄 Estado de secciones reiniciado para: ${idioma}`);
    }

    function restaurarTextoOriginal() {
        window.location.reload();
    }

    function actualizarBotonIdioma() {
        const botonText = document.getElementById("btn-traducir-text");
        if (botonText) {
            botonText.textContent = idiomas[idiomaActual] || idiomaActual.toUpperCase();
        }
    }

    function aplicarTraduccionCache(lang) {
        if (lang === "es") return true;
        
        const cache = traduccionesCache[lang];
        if (!cache || Object.keys(cache).length === 0) {
            return false;
        }
        
        const nodos = obtenerNodosTraducibles();
        let contador = 0;
        
        nodos.forEach(n => {
            const original = n.textContent.trim();
            if (cache[original]) {
                n.textContent = cache[original];
                contador++;
            }
        });
        
        console.log(`✅ Aplicadas ${contador} traducciones desde cache`);
        return contador > 0;
    }

    async function traducirPagina(lang) {
        if (lang === "es") return;
        
        const nodos = obtenerNodosTraducibles();
        const textos = Array.from(new Set(nodos.map(n => n.textContent.trim())))
                            .filter(texto => 
                                texto.length > 0 && 
                                !/^[\d\s\.:\/\-,]+$/.test(texto) &&
                                !traduccionesCache[lang]?.[texto]
                            );
        
        if (textos.length === 0) return;
        
        try {
            const resp = await fetch("/traducir", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ textos, idioma: lang })
            });
            
            if (!resp.ok) throw new Error(`Error HTTP: ${resp.status}`);
            
            const data = await resp.json();
            if (data.traducciones) {
                const nuevosMappings = {};
                textos.forEach((t, i) => {
                    const traducido = data.traducciones[i];
                    if (traducido && traducido !== t) {
                        nuevosMappings[t] = traducido;
                    }
                });
                
                traduccionesCache[lang] = { ...traduccionesCache[lang], ...nuevosMappings };
                localStorage.setItem(cacheKey, JSON.stringify(traduccionesCache));
                aplicarTraducciones(nuevosMappings);
                
                console.log(`💾 Cache actualizado con ${Object.keys(nuevosMappings).length} nuevas traducciones`);
            }
        } catch (error) {
            console.error("⚠️ Error en traducción:", error);
        }
    }

    function aplicarTraducciones(traducciones) {
        const nodos = obtenerNodosTraducibles();
        let contador = 0;
        
        nodos.forEach(n => {
            const original = n.textContent.trim();
            if (traducciones[original]) {
                n.textContent = traducciones[original];
                contador++;
            }
        });
        
        console.log(`🔄 Aplicadas ${contador} nuevas traducciones`);
    }

    function obtenerNodosTraducibles() {
        const nodos = [];
        const elementosIgnorados = ['script', 'style', 'noscript', 'svg', 'canvas', 'code', 'pre'];
        
        const walker = document.createTreeWalker(
            document.body, 
            NodeFilter.SHOW_TEXT, 
            {
                acceptNode: function(node) {
                    if (!node.textContent || node.textContent.trim().length === 0) return NodeFilter.FILTER_REJECT;
                    const parent = node.parentElement;
                    if (!parent) return NodeFilter.FILTER_REJECT;
                    const tagName = parent.tagName.toLowerCase();
                    if (elementosIgnorados.includes(tagName)) return NodeFilter.FILTER_REJECT;
                    if (parent.isContentEditable || ['INPUT','TEXTAREA','SELECT'].includes(parent.tagName)) return NodeFilter.FILTER_REJECT;
                    const texto = node.textContent.trim();
                    if (/^[\d\s\.:\/\-,%°°#@!$&*()+=\[\]{}|\\:;"'<>\?]+$/.test(texto)) return NodeFilter.FILTER_REJECT;
                    if (/[A-Za-zÀ-ÖØ-öø-ÿÑñ]/.test(texto)) return NodeFilter.FILTER_ACCEPT;
                    return NodeFilter.FILTER_REJECT;
                }
            }
        );
        
        let currentNode;
        while (currentNode = walker.nextNode()) {
            nodos.push(currentNode);
        }
        
        return nodos;
    }

    // Funciones globales
    window.recargarTraduccion = () => aplicarTraduccionCache(idiomaActual);
    window.cambiarIdiomaGlobal = cambiarIdioma;
    window.getIdiomaActual = () => idiomaActual;
    window.reiniciarTraduccionesSecciones = () => {
        seccionesTraducidas = {};
        localStorage.removeItem(seccionesTraducidasKey);
        console.log("🔄 Estado de secciones reiniciado completamente");
    };
    
    console.log("🌐 Sistema de traducción optimizado - Idioma:", idiomaActual);
});
</script>


<script>
document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById('btn-notificaciones');
    const menu = document.getElementById('noti-menu');
    const badge = document.getElementById('noti-count');

    let ultimaCantidad = 0;        
    let ultimaMedicionId = null;  
    const audio = new Audio("{{ asset('sounds/alert.wav') }}");


    if (btn && menu) {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.toggle('hidden');


            if (!menu.classList.contains('hidden')) {
                badge.classList.add('hidden');
                badge.textContent = '0';
                ultimaCantidad = 0;

                localStorage.setItem('ultimaMedicionVista', ultimaMedicionId ?? '');
            }
        });


        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    }


    async function cargarNotificaciones() {
        try {
            const resp = await fetch('/notificaciones');
            const data = await resp.json();

            if (!data || !Array.isArray(data.notificaciones)) return;

            const notis = data.notificaciones;
            const idMedicion = data.id_medicion ?? null;


            const html = notis.length > 0
                ? notis.map(n => `
                    <div class="flex items-start gap-2 p-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="ri-error-warning-line ${n.tipo === 'alto' ? 'text-red-500' : 'text-blue-500'} mt-0.5"></i>
                        <p class="text-sm ${n.tipo === 'alto' ? 'text-red-700 dark:text-red-400' : 'text-blue-700 dark:text-blue-400'}">
                            ${n.mensaje}
                        </p>
                    </div>
                `).join('')
                : '<p class="text-sm text-gray-500 dark:text-gray-400 text-center py-2">No hay notificaciones</p>';

            menu.innerHTML = html;


            const ultimaVista = localStorage.getItem('ultimaMedicionVista');

            if (notis.length > 0 && idMedicion && idMedicion !== ultimaVista) {

                badge.textContent = notis.length;
                badge.classList.remove('hidden');
                audio.play().catch(() => {
                    console.log("🔇 Sonido bloqueado hasta interacción del usuario");
                });
                ultimaCantidad = notis.length;
                ultimaMedicionId = idMedicion;
            } else if (notis.length === 0) {

                badge.classList.add('hidden');
                badge.textContent = '0';
            }
        } catch (err) {
            console.error("Error al obtener notificaciones:", err);
        }
    }


    cargarNotificaciones();
    setInterval(cargarNotificaciones, 60000);
});
</script>



        @stack('scripts')
    </body>
</html>
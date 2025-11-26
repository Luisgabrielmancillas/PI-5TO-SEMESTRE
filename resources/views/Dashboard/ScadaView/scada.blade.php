<x-app-layout title="Scada | HydroBox">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Interfaz Scada') }}
            </h2>

            <span class="pill pill-emerald ml-auto inline-flex items-center">
                Hortaliza seleccionada:
                @if(!empty($selectedCrop))
                    <b class="ml-1">{{ $selectedCrop->nombre }}</b>
                @else
                    <b class="ml-1">No hay</b>
                @endif
            </span>
        </div>
    </x-slot>

    {{-- Mensaje solo para móviles --}}
    <div class="block md:hidden px-4 py-8">
        <p class="text-center text-sm text-slate-500 dark:text-slate-400">
            La interfaz SCADA solo está disponible en pantallas de escritorio.
        </p>
    </div>

    {{-- Interfaz SCADA: solo en desktop --}}
    <div class="bg-container hidden md:block">
        
        {{-- Efecto de luz de lámpara --}}
        <img id="lighting" 
             src="{{ asset('images/Iluminacion1.png') }}"
             alt="Efecto de luz" 
             class="absolute lighting-image z-5 hidden transition-opacity duration-500">

        {{-- LÁMPARA (id_actuador = 5 -> light) --}}
        <div id="lampControlGroup"
             data-actuator-id="5"
             class="absolute lamp-container left-[63%] -translate-x-1/2 flex flex-col items-center z-10 cursor-pointer">
            
            <button id="lampToggle" 
                    class="cursor-pointer focus:outline-none p-0 border-none bg-transparent transform hover:scale-105 transition duration-300">
                
                <img id="lampImage" src="{{ asset('images/lampara.png') }}" alt="Lámpara" class="w-96 h-auto">
            </button>
            
            <span id="lampStatus" 
                  class="mt-1 text-sm text-black px-3 py-1 rounded-full 
                         bg-gray-50 border border-2 border-gray-300 transition duration-300 
                         flex items-center space-x-2 whitespace-nowrap">
                
                <div id="lampDot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                
                <span id="lampText">Lámpara: Off</span>
            </span>
        </div>
        
        {{-- VENTILADOR (id_actuador = 6 -> fan) --}}
        <div id="fanToggle"
             data-actuator-id="6"
             class="absolute top-[55px] left-[37%] z-20 flex flex-col items-center cursor-pointer">
            <img id="fanImage" src="{{ asset('images/fan_off.png') }}" alt="Ventilador" class="w-24 h-auto transform hover:scale-105 transition duration-300">
            
            <span id="fanStatus" 
                  class="text-sm text-black px-3 py-1 rounded-full 
                         bg-gray-50 border border-2 border-gray-300 transition duration-300 
                         flex items-center space-x-2 whitespace-nowrap">
                <div id="fanDot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="fanText">FAN: Off</span>
            </span>
        </div>
        

        {{-- CÁMARA: vista + pill de estado --}}
        <div id="cameraToggle" class="absolute top-[200px] left-[80%] z-20 flex flex-col items-center">
            
            {{-- Recuadro con la imagen de la cámara --}}
            <div class="border border-gray-300 rounded-xl overflow-hidden mb-2 bg-black">
                <img
                    id="cameraStream"
                    src="http://10.42.62.18:8000/stream.mjpg"
                    alt="Cámara HydroBox"
                    class="w-96 h-60 object-cover"
                >
            </div>

            {{-- Mini componente de estado (por defecto: OFF) --}}
            <span id="cameraStatus" 
                class="mt-2 text-sm text-black px-3 py-1 rounded-full 
                        bg-gray-50 border border-2 border-gray-300 transition duration-300 
                        flex items-center space-x-2 whitespace-nowrap">
                <div id="cameraDot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="cameraText">Cámara: Off</span>
            </span>
        </div>


        {{-- BLOQUE DE SENSORES (se refresca cada 10s) --}}
        <div id="scada-sensors">
            @include('Dashboard.ScadaView._sensors-block', ['latest' => $latest])
        </div>

        {{-- BOMBAS DOSIFICADORAS --}}
        {{-- BD1 = FloraMicro = id_actuador 2 --}}
        <div id="bd1Toggle"
             data-actuator-id="2"
             class="absolute top-[45%] left-[20.7%] z-20 flex flex-col items-center cursor-pointer">
            <img id="bd1Image" src="{{ asset('images/bomba_dosificadora.png') }}" alt="Bomba D. N1" class="w-10 h-auto transform hover:scale-110 transition duration-300">
            <span id="bd1Status" class="text-sm text-black px-3 py-1 rounded-full bg-gray-50 border border-2 border-gray-300 transition duration-300 flex items-center space-x-2 whitespace-nowrap">
                <div id="bd1Dot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="bd1Text">BD1: Off</span>
            </span>
        </div>

        {{-- BD2 = FloraGro = id_actuador 1 --}}
        <div id="bd2Toggle"
             data-actuator-id="1"
             class="absolute top-[37%] left-[25.8%] z-20 flex flex-col items-center cursor-pointer">
            <img id="bd2Image" src="{{ asset('images/bomba_dosificadora.png') }}" alt="Bomba D. N2" class="w-10 h-auto transform hover:scale-110 transition duration-300">
            <span id="bd2Status" class="text-sm text-black px-3 py-1 rounded-full bg-gray-50 border border-2 border-gray-300 transition duration-300 flex items-center space-x-2 whitespace-nowrap">
                <div id="bd2Dot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="bd2Text">BD2: Off</span>
            </span>
        </div>

        {{-- BD3 = FloraBloom = id_actuador 3 --}}
        <div id="bd3Toggle"
             data-actuator-id="3"
             class="absolute top-[30%] left-[31.6%] z-20 flex flex-col items-center cursor-pointer">
            <img id="bd3Image" src="{{ asset('images/bomba_dosificadora.png') }}" alt="Bomba D. N3" class="w-10 h-auto transform hover:scale-110 transition duration-300">
            <span id="bd3Status" class="text-sm text-black px-3 py-1 rounded-full bg-gray-50 border border-2 border-gray-300 transition duration-300 flex items-center space-x-2 whitespace-nowrap">
                <div id="bd3Dot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="bd3Text">BD3: Off</span>
            </span>
        </div>

        {{-- BOMBA DE AGUA = id_actuador 4 --}}
        <div id="baToggle"
             data-actuator-id="4"
             class="absolute top-[83.5%] left-[47.8%] z-20 flex flex-col items-center cursor-pointer">
            <img id="baImage" src="{{ asset('images/bomba_agua.png') }}" alt="Bomba de Agua" class="w-20 h-auto transform hover:scale-105 transition duration-300">
            
            <span id="baStatus" 
                  class="text-sm text-black px-3 py-1 rounded-full 
                         bg-gray-50 border border-2 border-gray-300 transition duration-300 
                         flex items-center space-x-2 whitespace-nowrap">
                <div id="baDot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="baText">BA: Off</span>
            </span>
        </div>
        
        {{-- TERMINOLOGÍA --}}
        <div class="absolute bottom-8 right-16 z-10 p-4 rounded text-xs">
            <img id="terminologiaImage" src="{{ asset('images/terminologia.jpeg') }}" alt="Terminología" class="w-96 h-auto border border-black">
        </div>

    </div>

    @push('scripts')
        <script>
            // =======================================================
            // CONSTANTES DE RUTAS / ESTADOS INICIALES
            // =======================================================
            const toggleUrl      = @json(route('scada.toggle'));
            const actuatorStates = @json($actuatorStatesById ?? []);
            const statesUrl      = @json(route('scada.states'));
            const csrfTokenMeta  = document.querySelector('meta[name="csrf-token"]');
            const csrfToken      = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

            // =======================================================
            // CÓDIGO JAVASCRIPT SCADA (actuadores + refresco sensores)
            // =======================================================
            
            // --- LÁMPARA ---
            const lampToggle       = document.getElementById('lampToggle');
            const lampControlGroup = document.getElementById('lampControlGroup'); 
            const lampStatus       = document.getElementById('lampStatus');
            const lampDot          = document.getElementById('lampDot'); 
            const lampText         = document.getElementById('lampText');
            const lightingImage    = document.getElementById('lighting'); 
            let   isLampOn         = false;
            const lampActuatorId   = lampControlGroup ? lampControlGroup.dataset.actuatorId : null;

            // --- BOMBAS DOSIFICADORAS ---
            const bd1Toggle = document.getElementById('bd1Toggle');
            const bd1Image  = document.getElementById('bd1Image');
            const bd1Dot    = document.getElementById('bd1Dot'); 
            const bd1Text   = document.getElementById('bd1Text');
            let   isBD1On   = { value: false }; 

            const bd2Toggle = document.getElementById('bd2Toggle');
            const bd2Image  = document.getElementById('bd2Image');
            const bd2Dot    = document.getElementById('bd2Dot'); 
            const bd2Text   = document.getElementById('bd2Text');
            let   isBD2On   = { value: false };

            const bd3Toggle = document.getElementById('bd3Toggle');
            const bd3Image  = document.getElementById('bd3Image');
            const bd3Dot    = document.getElementById('bd3Dot'); 
            const bd3Text   = document.getElementById('bd3Text');
            let   isBD3On   = { value: false };
            
            // --- BOMBA DE AGUA (BA) ---
            const baToggle = document.getElementById('baToggle');
            const baImage  = document.getElementById('baImage');
            const baDot    = document.getElementById('baDot'); 
            const baText   = document.getElementById('baText');
            let   isBAOn   = { value: false };
            
            // --- VENTILADOR (FAN) ---
            const fanToggle = document.getElementById('fanToggle');
            const fanImage  = document.getElementById('fanImage');
            const fanDot    = document.getElementById('fanDot');
            const fanText   = document.getElementById('fanText');
            let   isFanOn   = { value: false };
            
            // --- CLASES DE ESTADO ---
            const statusClassesOff = ['bg-gray-50', 'border-gray-300'];
            const dotClassesOff    = ['bg-gray-500'];
            const statusClassesOn  = ['bg-green-100', 'border-green-300'];
            const dotClassesOn     = ['bg-green-700']; 


            // =======================================================
            // ESTADO DE CÁMARA SEGÚN SI CARGA EL STREAM
            // =======================================================
            const cameraStream = document.getElementById('cameraStream');
            const cameraStatus = document.getElementById('cameraStatus');
            const cameraDot    = document.getElementById('cameraDot');
            const cameraText   = document.getElementById('cameraText');

            function setCameraState(isOn) {
                if (!cameraStatus || !cameraDot || !cameraText) return;

                if (isOn) {
                    cameraText.textContent = 'Cámara: On';
                    cameraStatus.classList.remove(...statusClassesOff);
                    cameraStatus.classList.add(...statusClassesOn);
                    cameraDot.classList.remove(...dotClassesOff);
                    cameraDot.classList.add(...dotClassesOn);
                } else {
                    cameraText.textContent = 'Cámara: Off';
                    cameraStatus.classList.remove(...statusClassesOn);
                    cameraStatus.classList.add(...statusClassesOff);
                    cameraDot.classList.remove(...dotClassesOn);
                    cameraDot.classList.add(...dotClassesOff);
                }
            }

            // Por default la dejamos en OFF
            setCameraState(false);

            if (cameraStream) {
                cameraStream.addEventListener('load', () => {
                    setCameraState(true);
                });

                cameraStream.addEventListener('error', () => {
                    setCameraState(false);
                });
            }

            // =======================================================
            // HELPER PARA PINTAR UN ACTUADOR
            // =======================================================
            function paintActuator(toggleEl, imageEl, dotEl, textEl, label, isOn, imageSrcOn, imageSrcOff, applyShake = true) {
                if (!toggleEl) return;

                const statusSpan = toggleEl.querySelector('span');

                textEl.textContent = `${label}: ${isOn ? 'On' : 'Off'}`;

                if (applyShake && imageEl) {
                    if (isOn) imageEl.classList.add('animate-shake');
                    else      imageEl.classList.remove('animate-shake');
                }

                if (imageEl && imageSrcOn && imageSrcOff) {
                    imageEl.src = isOn ? imageSrcOn : imageSrcOff;
                }

                if (statusSpan && dotEl) {
                    if (isOn) {
                        statusSpan.classList.remove(...statusClassesOff);
                        statusSpan.classList.add(...statusClassesOn);
                        dotEl.classList.remove(...dotClassesOff);
                        dotEl.classList.add(...dotClassesOn);
                    } else {
                        statusSpan.classList.remove(...statusClassesOn);
                        statusSpan.classList.add(...statusClassesOff);
                        dotEl.classList.remove(...dotClassesOn);
                        dotEl.classList.add(...dotClassesOff);
                    }
                }
            }

            // =======================================================
            // ENVIAR TOGGLE AL BACKEND (Ajax -> Laravel -> MQTT)
            // =======================================================
            async function sendActuatorToggleToServer(actuatorId, isOn) {
                if (!toggleUrl || !actuatorId) return;

                try {
                    await fetch(toggleUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            id_actuador: parseInt(actuatorId, 10),
                            on: !!isOn,
                        }),
                    });
                } catch (e) {
                    console.error('Error enviando toggle actuador:', e);
                }
            }

            // =======================================================
            // FUNCIÓN GENÉRICA DE ACTUADORES (BD1, BD2, BD3, BA, FAN)
            // =======================================================
            function setupActuatorToggle(
                toggleEl,
                imageEl,
                dotEl,
                textEl,
                label,
                isActuatorOnRef,
                imageSrcOn,
                imageSrcOff,
                applyShake = true
            ) {
                if (!toggleEl) return;

                const actuatorId = toggleEl.dataset.actuatorId;

                const initial = actuatorId && actuatorId in actuatorStates
                    ? actuatorStates[actuatorId] === 1
                    : false;

                isActuatorOnRef.value = initial;
                paintActuator(toggleEl, imageEl, dotEl, textEl, label, initial, imageSrcOn, imageSrcOff, applyShake);

                toggleEl.addEventListener('click', function() {
                    isActuatorOnRef.value = !isActuatorOnRef.value;
                    const isOn = isActuatorOnRef.value;

                    paintActuator(toggleEl, imageEl, dotEl, textEl, label, isOn, imageSrcOn, imageSrcOff, applyShake);

                    if (actuatorId) {
                        sendActuatorToggleToServer(actuatorId, isOn);
                    }
                });
            }

            // =======================================================
            // LÓGICA DE LÁMPARA (usa DB + MQTT pero con overlay de luz)
            // =======================================================
            function applyLampUi(isOn) {
                if (!lampControlGroup) return;

                isLampOn = isOn;

                const lampContainerStyle = window.getComputedStyle(lampControlGroup);
                const lampLeft = lampContainerStyle.getPropertyValue('left');
                const lampTop  = lampContainerStyle.getPropertyValue('top');

                if (isOn) {
                    lampText.textContent = 'Lámpara: On';
                    lightingImage.classList.remove('hidden');
                    lightingImage.style.left = lampLeft;
                    lightingImage.style.top  = lampTop;

                    lampStatus.classList.remove(...statusClassesOff);
                    lampStatus.classList.add(...statusClassesOn);
                    lampDot.classList.remove(...dotClassesOff);
                    lampDot.classList.add(...dotClassesOn);
                } else {
                    lampText.textContent = 'Lámpara: Off';
                    lightingImage.classList.add('hidden');
                    lampStatus.classList.remove(...statusClassesOn);
                    lampStatus.classList.add(...statusClassesOff);
                    lampDot.classList.remove(...dotClassesOn);
                    lampDot.classList.add(...dotClassesOff);
                }
            }

            async function toggleLamp() {
                const next = !isLampOn;
                applyLampUi(next);

                if (lampActuatorId) {
                    await sendActuatorToggleToServer(lampActuatorId, next);
                }
            }

            // Estado inicial de la lámpara desde la DB
            if (lampActuatorId && actuatorStates[lampActuatorId] === 1) {
                applyLampUi(true);
            } else {
                applyLampUi(false);
            }

            if (lampControlGroup) {
                lampControlGroup.addEventListener('click', function(event) {
                    if (event.target.id === 'lampStatus' || event.target.tagName === 'SPAN' || event.target.tagName === 'DIV') {
                        toggleLamp();
                    }
                });
            }

            if (lampToggle) {
                lampToggle.addEventListener('click', function(event) {
                    event.stopPropagation();
                    toggleLamp();
                });
            }

            // =======================================================
            // CONFIGURACIÓN DE ACTUADORES (BD1, BD2, BD3, BA, FAN)
            // =======================================================
            setupActuatorToggle(
                fanToggle,
                fanImage,
                fanDot,
                fanText,
                'FAN',
                isFanOn,
                '{{ asset('images/fan.gif') }}',
                '{{ asset('images/fan_off.png') }}',
                false
            );
            
            setupActuatorToggle(bd1Toggle, bd1Image, bd1Dot, bd1Text, 'BD1', isBD1On);
            setupActuatorToggle(bd2Toggle, bd2Image, bd2Dot, bd2Text, 'BD2', isBD2On);
            setupActuatorToggle(bd3Toggle, bd3Image, bd3Dot, bd3Text, 'BD3', isBD3On);
            setupActuatorToggle(baToggle,  baImage,  baDot,  baText,  'BA',  isBAOn);

            // =======================================================
            // REFRESCO PERIÓDICO DE SENSORES (cada 10s, HTML Blade)
            // =======================================================
            const scadaSensorsContainer = document.getElementById('scada-sensors');
            const scadaBlockUrl = @json(route('scada.block'));

            async function refreshSensors() {
                if (!scadaSensorsContainer) return;

                try {
                    const response = await fetch(scadaBlockUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) return;

                    const html = await response.text();
                    scadaSensorsContainer.innerHTML = html;
                } catch (e) {
                    console.error('Error al actualizar sensores SCADA:', e);
                }
            }

            // =======================================================
            // REFRESCO PERIÓDICO DE ESTADOS DE ACTUADORES
            // =======================================================
            async function refreshActuatorStates() {
                if (!statesUrl) return;
                try {
                    const resp = await fetch(statesUrl, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!resp.ok) return;
                    const data = await resp.json();

                    // Actualizamos cada actuador conocido (por id_actuador)
                    // BD1 = 2
                    if (data['2'] !== undefined) {
                        const on = data['2'] === 1;
                        isBD1On.value = on;
                        paintActuator(bd1Toggle, bd1Image, bd1Dot, bd1Text, 'BD1', on);
                    }
                    // BD2 = 1
                    if (data['1'] !== undefined) {
                        const on = data['1'] === 1;
                        isBD2On.value = on;
                        paintActuator(bd2Toggle, bd2Image, bd2Dot, bd2Text, 'BD2', on);
                    }
                    // BD3 = 3
                    if (data['3'] !== undefined) {
                        const on = data['3'] === 1;
                        isBD3On.value = on;
                        paintActuator(bd3Toggle, bd3Image, bd3Dot, bd3Text, 'BD3', on);
                    }
                    // BA = 4
                    if (data['4'] !== undefined) {
                        const on = data['4'] === 1;
                        isBAOn.value = on;
                        paintActuator(baToggle, baImage, baDot, baText, 'BA', on);
                    }
                    // Lámpara = 5
                    if (data['5'] !== undefined) {
                        const on = data['5'] === 1;
                        applyLampUi(on);
                    }
                    // FAN = 6
                    if (data['6'] !== undefined) {
                        const on = data['6'] === 1;
                        isFanOn.value = on;
                        paintActuator(
                            fanToggle,
                            fanImage,
                            fanDot,
                            fanText,
                            'FAN',
                            on,
                            '{{ asset('images/fan.gif') }}',
                            '{{ asset('images/fan_off.png') }}',
                            false
                        );
                    }
                } catch (e) {
                    console.error('Error refrescando estados de actuadores:', e);
                }
            }

            // Timers
            setInterval(refreshSensors, 10000);      // sensores cada 10s
            setInterval(refreshActuatorStates, 5000); // actuadores cada 5s
        </script>
    @endpush

    @push('styles')
        <style>
            :root {
                --top-offset: 0px; 
                --image-lift: 35px;
                /* Desplazamiento horizontal de TODO el SCADA */
                --scada-shift-x: -120px; /* mueve todo ~120px a la izquierda */
            }

            /* Tema claro: fondo blanco */
            .bg-container {
                background-image: url('{{ asset('images/Fondo_W.png') }}');
                margin-top: 0;
                min-height: calc(100vh + 70px);
                position: relative;
                width: 100%;
                background-size: 100% auto;
                background-position: calc(50% - 5px) calc(50% - var(--image-lift));
                background-repeat: no-repeat;
                overflow: hidden;
                transform: translateX(var(--scada-shift-x));
            }

            /* Tema oscuro: fondo B */
            .dark .bg-container {
                background-image: url('{{ asset('images/Fondo_B.png') }}');
            }

            .lamp-container { top: 0px; } 

            .lighting-image { 
                width: 320px; 
                margin-top: 30px;
                margin-left: 5px;
                opacity: 0.8; 
                position: absolute;
                transform: translateX(-50%);
            }

            .fan-container { top: 20px; left: 10%; } 
            
            @keyframes shake {
                0% { transform: translateX(0); }
                25% { transform: translateX(-1px); }
                50% { transform: translateX(1px); }
                75% { transform: translateX(-1px); }
                100% { transform: translateX(0); }
            }

            .animate-shake {
                animation: shake 0.2s infinite; 
            }
        </style>
    @endpush
</x-app-layout>
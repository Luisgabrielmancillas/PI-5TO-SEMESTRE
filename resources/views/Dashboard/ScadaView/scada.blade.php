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

    <div class="bg-container">
        
        <img id="lighting" 
             src="{{ asset('images/Iluminacion1.png') }}"
             alt="Efecto de luz" 
             class="absolute lighting-image z-5 hidden transition-opacity duration-500">


        <div id="lampControlGroup" class="absolute lamp-container left-[63%] -translate-x-1/2 flex flex-col items-center z-10 cursor-pointer">
            
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
        
        
        <div id="fanToggle" class="absolute top-[55px] left-[37%] z-20 flex flex-col items-center cursor-pointer">
            <img id="fanImage" src="{{ asset('images/fan_off.png') }}" alt="Ventilador" class="w-24 h-auto transform hover:scale-105 transition duration-300">
            
            <span id="fanStatus" 
                  class="text-sm text-black px-3 py-1 rounded-full 
                         bg-gray-50 border border-2 border-gray-300 transition duration-300 
                         flex items-center space-x-2 whitespace-nowrap">
                <div id="fanDot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="fanText">FAN: Off</span>
            </span>
        </div>
        
        <div id="cameraToggle" class="absolute top-[200px] left-[80%] z-20 flex flex-col items-center cursor-pointer">
            <img id="cameraImage" src="{{ asset('images/camera.png') }}" alt="Cámara" class="w-24 h-auto transform hover:scale-105 transition duration-300">
            
            <span id="cameraStatus" 
                  class="mt-1 text-sm text-black px-3 py-1 rounded-full 
                         bg-gray-50 border border-2 border-gray-300 transition duration-300 
                         flex items-center space-x-2 whitespace-nowrap">
                <div id="cameraDot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="cameraText">Cámara: Off</span>
            </span>
        </div>

        
        <div id="tempSensor" class="absolute top-[78px] left-[47%] z-20 flex items-center">
            <span id="tempValue" class="text-sm text-black px-3 py-1 rounded-full bg-gray-50 border border-2 border-gray-300 shadow-md whitespace-nowrap">
                Temp: 25.0°C
            </span> 
            <img id="tempImage" src="{{ asset('images/temperatura.png') }}" alt="Sensor de Temperatura" class="ml-2 h-24 w-auto">
        </div>

        <div id="humSensor" class="absolute top-[78px] left-[70%] z-20 flex items-center"> 
            <img id="humImage" src="{{ asset('images/humedad.png') }}" alt="Sensor de Humedad" class="h-24 w-auto">
            <span id="humValue" class="border border-2 text-sm text-black px-3 py-1 rounded-full bg-gray-50 border-gray-300 shadow-md whitespace-nowrap">
                Hum: 65%
            </span>
        </div>
        
        <div class="absolute top-[79%] left-[32%] z-20 flex flex-col space-y-2 items-start">
            
            <span class="text-sm text-black px-3 py-1 rounded-full 
                         bg-gray-50 border border-2 border-gray-300 shadow-md whitespace-nowrap">
                T: 22.5°C
            </span>

            <span class="text-sm text-black px-3 py-1 rounded-full 
                         bg-gray-50 border border-2 border-gray-300 shadow-md whitespace-nowrap">
                ORP: 500 mV
            </span>

            <span class="text-sm text-black px-3 py-1 rounded-full 
                         bg-gray-50 border border-2 border-gray-300 shadow-md whitespace-nowrap">
                pH: 6.2
            </span>

        </div>
        
        <div class="absolute top-[75%] left-[58%] z-20">
            <span class="text-sm text-black px-3 py-1 rounded-full 
                         bg-gray-50 border border-2 border-gray-300 shadow-md whitespace-nowrap">
                Ult: 27cm
            </span>
        </div>


        <div id="bd1Toggle" class="absolute top-[45%] left-[20.7%] z-20 flex flex-col items-center cursor-pointer">
            <img id="bd1Image" src="{{ asset('images/bomba_dosificadora.png') }}" alt="Bomba D. N1" class="w-10 h-auto transform hover:scale-110 transition duration-300">
            <span id="bd1Status" class="text-sm text-black px-3 py-1 rounded-full bg-gray-50 border border-2 border-gray-300 transition duration-300 flex items-center space-x-2 whitespace-nowrap">
                <div id="bd1Dot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="bd1Text">BD1: Off</span>
            </span>
        </div>

        <div id="bd2Toggle" class="absolute top-[37%] left-[25.8%] z-20 flex flex-col items-center cursor-pointer">
            <img id="bd2Image" src="{{ asset('images/bomba_dosificadora.png') }}" alt="Bomba D. N2" class="w-10 h-auto transform hover:scale-110 transition duration-300">
            <span id="bd2Status" class="text-sm text-black px-3 py-1 rounded-full bg-gray-50 border border-2 border-gray-300 transition duration-300 flex items-center space-x-2 whitespace-nowrap">
                <div id="bd2Dot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="bd2Text">BD2: Off</span>
            </span>
        </div>

        <div id="bd3Toggle" class="absolute top-[30%] left-[31.6%] z-20 flex flex-col items-center cursor-pointer">
            <img id="bd3Image" src="{{ asset('images/bomba_dosificadora.png') }}" alt="Bomba D. N3" class="w-10 h-auto transform hover:scale-110 transition duration-300">
            <span id="bd3Status" class="text-sm text-black px-3 py-1 rounded-full bg-gray-50 border border-2 border-gray-300 transition duration-300 flex items-center space-x-2 whitespace-nowrap">
                <div id="bd3Dot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="bd3Text">BD3: Off</span>
            </span>
        </div>

        <div id="baToggle" class="absolute top-[83.5%] left-[47.8%] z-20 flex flex-col items-center cursor-pointer">
            <img id="baImage" src="{{ asset('images/bomba_agua.png') }}" alt="Bomba de Agua" class="w-20 h-auto transform hover:scale-105 transition duration-300">
            
            <span id="baStatus" 
                  class="text-sm text-black px-3 py-1 rounded-full 
                         bg-gray-50 border border-2 border-gray-300 transition duration-300 
                         flex items-center space-x-2 whitespace-nowrap">
                <div id="baDot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="baText">BA: Off</span>
            </span>
        </div>
        
        <div class="absolute bottom-8 right-16 z-10 p-4 rounded text-xs">
            <img id="terminologiaImage" src="{{ asset('images/terminologia.png') }}" alt="Terminología" class="w-96 h-auto border border-black">
        </div>


    </div>
    @push('scripts')
        <script>
            // =======================================================
            // CÓDIGO JAVASCRIPT
            // =======================================================
            
            // --- LÁMPARA ---
            const lampToggle = document.getElementById('lampToggle');
            const lampControlGroup = document.getElementById('lampControlGroup'); 
            const lampStatus = document.getElementById('lampStatus');
            const lampDot = document.getElementById('lampDot'); 
            const lampText = document.getElementById('lampText');
            const lightingImage = document.getElementById('lighting'); 
            let isLampOn = false;

            // --- BOMBAS DOSIFICADORAS ---
            const bd1Toggle = document.getElementById('bd1Toggle');
            const bd1Image = document.getElementById('bd1Image');
            const bd1Dot = document.getElementById('bd1Dot'); 
            const bd1Text = document.getElementById('bd1Text');
            let isBD1On = { value: false }; 

            const bd2Toggle = document.getElementById('bd2Toggle');
            const bd2Image = document.getElementById('bd2Image');
            const bd2Dot = document.getElementById('bd2Dot'); 
            const bd2Text = document.getElementById('bd2Text');
            let isBD2On = { value: false };

            const bd3Toggle = document.getElementById('bd3Toggle');
            const bd3Image = document.getElementById('bd3Image');
            const bd3Dot = document.getElementById('bd3Dot'); 
            const bd3Text = document.getElementById('bd3Text');
            let isBD3On = { value: false };
            
            // --- BOMBA DE AGUA (BA) ---
            const baToggle = document.getElementById('baToggle');
            const baImage = document.getElementById('baImage');
            const baDot = document.getElementById('baDot'); 
            const baText = document.getElementById('baText');
            let isBAOn = { value: false };
            
            // --- VENTILADOR (FAN) ---
            const fanToggle = document.getElementById('fanToggle');
            const fanImage = document.getElementById('fanImage');
            const fanDot = document.getElementById('fanDot');
            const fanText = document.getElementById('fanText');
            let isFanOn = { value: false };
            
            // --- CÁMARA (CAM) ---
            const cameraToggle = document.getElementById('cameraToggle');
            const cameraImage = document.getElementById('cameraImage');
            const cameraDot = document.getElementById('cameraDot');
            const cameraText = document.getElementById('cameraText');
            let isCameraOn = { value: false };


            // --- CLASES DE ESTADO ---
            const statusClassesOff = ['bg-gray-50', 'border-gray-300'];
            const dotClassesOff = ['bg-gray-500'];
            const statusClassesOn = ['bg-green-100', 'border-green-300'];
            const dotClassesOn = ['bg-green-700']; 

            // =======================================================
            // FUNCIÓN AUXILIAR GENÉRICA DE ACTUADORES
            // =======================================================
            function setupActuatorToggle(toggleId, imageElement, dotElement, textElement, initialName, isActuatorOnRef, imageSrcOn, imageSrcOff, applyShake = true) {
                
                toggleId.addEventListener('click', function() {
                    isActuatorOnRef.value = !isActuatorOnRef.value; 
                    const isOn = isActuatorOnRef.value;

                    const statusSpan = toggleId.querySelector('span');
                    
                    if (isOn) {
                        textElement.textContent = `${initialName}: On`;
                        
                        if (applyShake) {
                            imageElement.classList.add('animate-shake'); 
                        }
                        if (imageSrcOn) { 
                            imageElement.src = imageSrcOn; 
                        } 
                        
                        statusSpan.classList.remove(...statusClassesOff);
                        statusSpan.classList.add(...statusClassesOn);
                        dotElement.classList.remove(...dotClassesOff);
                        dotElement.classList.add(...dotClassesOn);

                    } else {
                        textElement.textContent = `${initialName}: Off`;
                        
                        if (applyShake) {
                            imageElement.classList.remove('animate-shake'); 
                        }
                        if (imageSrcOff) { 
                            imageElement.src = imageSrcOff; 
                        } 
                        
                        statusSpan.classList.remove(...statusClassesOn);
                        statusSpan.classList.add(...statusClassesOff);
                        dotElement.classList.remove(...dotClassesOn);
                        dotElement.classList.add(...dotClassesOff);
                    }
                });
            }


            // =======================================================
            // LÓGICA DE LÁMPARA (Control de toggle unificado y posición de luz)
            // =======================================================
            function toggleLamp() {
                isLampOn = !isLampOn; 
                
                const lampContainerStyle = window.getComputedStyle(lampControlGroup);
                const lampLeft = lampContainerStyle.getPropertyValue('left');
                const lampTop = lampContainerStyle.getPropertyValue('top');

                if (isLampOn) {
                    lampText.textContent = 'Lámpara: On';
                    lightingImage.classList.remove('hidden'); 
                    
                    lightingImage.style.left = lampLeft;
                    lightingImage.style.top = lampTop;

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
            
            // Listener unificado para el grupo de la lámpara
            lampControlGroup.addEventListener('click', function(event) {
                // Revisa si el clic fue en el span o en el espacio vacío del div
                if (event.target.id === 'lampStatus' || event.target.tagName === 'SPAN' || event.target.tagName === 'DIV') {
                    toggleLamp();
                }
            });
            
            // Listener para el botón de la lámpara (imagen)
            lampToggle.addEventListener('click', toggleLamp); // Se asegura que el botón (imagen) también llame a toggleLamp()

            // =======================================================
            // CONFIGURACIÓN DE ACTUADORES
            // =======================================================
            
            setupActuatorToggle(fanToggle, fanImage, fanDot, fanText, 'FAN', isFanOn, '{{ asset('images/fan.gif') }}', '{{ asset('images/fan_off.png') }}', false);
            
            setupActuatorToggle(bd1Toggle, bd1Image, bd1Dot, bd1Text, 'BD1', isBD1On);
            setupActuatorToggle(bd2Toggle, bd2Image, bd2Dot, bd2Text, 'BD2', isBD2On);
            setupActuatorToggle(bd3Toggle, bd3Image, bd3Dot, bd3Text, 'BD3', isBD3On);
            
            setupActuatorToggle(baToggle, baImage, baDot, baText, 'BA', isBAOn);
            
            setupActuatorToggle(cameraToggle, cameraImage, cameraDot, cameraText, 'Cámara', isCameraOn, null, null, false);


            // Inicialización de estados al cargar la página
            lampText.textContent = 'Lámpara: Off';
            fanText.textContent = 'FAN: Off';
            fanImage.src = '{{ asset('images/fan_off.png') }}'; 
            bd1Text.textContent = 'BD1: Off';
            bd2Text.textContent = 'BD2: Off';
            bd3Text.textContent = 'BD3: Off';
            baText.textContent = 'BA: Off';
            cameraText.textContent = 'Cámara: Off';
        </script>
    @endpush
    @push('styles')
        <style>
            :root {
                --top-offset: 0px; 
                --image-lift: 35px;
                --image-shift-x: 5px; 
            }

            .bg-container {
                background-image: url('{{ asset('images/Fondo_B.png') }}');
                margin-top: 0;
                min-height: calc(100vh + 100px);
                position: relative;
                width: 100%;
                background-size: 100% auto;
                background-position: calc(50% - var(--image-shift-x)) calc(50% - var(--image-lift));
                background-repeat: no-repeat;
                overflow: hidden;
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
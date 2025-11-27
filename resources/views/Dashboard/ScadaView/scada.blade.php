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

        {{-- CÁMARA --}}
        <div id="cameraToggle" class="absolute top-[200px] left-[80%] z-20 flex flex-col items-center">

            {{-- Recuadro con la imagen de la cámara (wrapper con id para hover/click) --}}
            <div id="cameraFrame"
                 class="camera-frame w-82 h-60 border border-gray-300 rounded-xl overflow-hidden mb-2 bg-black">
                <img
                    id="cameraStream"
                    src="http://10.42.62.18:8000/stream.mjpg"
                    alt="Cámara HydroBox"
                    class="w-full h-full object-cover"
                >
            </div>

            <span id="cameraStatus"
                  class="mt-2 text-sm text-black px-3 py-1 rounded-full
                        bg-gray-50 border border-2 border-gray-300 transition duration-300
                        flex items-center space-x-2 whitespace-nowrap">
                <div id="cameraDot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="cameraText">Cámara: Off</span>
            </span>
        </div>

        {{-- BLOQUE DE SENSORES --}}
        <div id="scada-sensors">
            @include('Dashboard.ScadaView._sensors-block', ['latest' => $latest])
        </div>

        {{-- BOMBAS DOSIFICADORAS --}}
        {{-- BD1 = Peristáltica A — FloraMicro = id_actuador 2 (IZQUIERDA) --}}
        <div id="bd1Toggle"
             data-actuator-id="2"
             class="absolute top-[45%] left-[20.7%] z-20 flex flex-col items-center cursor-pointer">
            <img id="bd1Image" src="{{ asset('images/bomba_dosificadora.png') }}" alt="Bomba D. N1" class="w-10 h-auto transform hover:scale-110 transition duration-300">
            <span id="bd1Status" class="text-sm text-black px-3 py-1 rounded-full bg-gray-50 border border-2 border-gray-300 transition duration-300 flex items-center space-x-2 whitespace-nowrap">
                <div id="bd1Dot" class="w-2 h-2 rounded-full bg-gray-500 transition duration-300"></div>
                <span id="bd1Text">BD1: Off</span>
            </span>
        </div>

        {{-- BD2 = Peristáltica B — FloraBloom = id_actuador 3 (CENTRO) --}}
        <div id="bd2Toggle"
             data-actuator-id="3"
             class="absolute top-[37%] left-[25.8%] z-20 flex flex-col items-center cursor-pointer">
            <img id="bd2Image" src="{{ asset('images/bomba_dosificadora.png') }}" alt="Bomba D. N2" class="w-10 h-auto transform hover:scale-110 transition duration-300">
            <span id="bd2Status" class="text-sm text-black px-3 py-1 rounded-full bg-gray-50 border border-2 border-gray-300 transition duración-300 flex items-center space-x-2 whitespace-nowrap">
                <div id="bd2Dot" class="w-2 h-2 rounded-full bg-gray-500 transition duración-300"></div>
                <span id="bd2Text">BD2: Off</span>
            </span>
        </div>

        {{-- BD3 = Peristáltica C — FloraGro = id_actuador 1 (DERECHA) --}}
        <div id="bd3Toggle"
             data-actuator-id="1"
             class="absolute top-[30%] left-[31.6%] z-20 flex flex-col items-center cursor-pointer">
            <img id="bd3Image" src="{{ asset('images/bomba_dosificadora.png') }}" alt="Bomba D. N3" class="w-10 h-auto transform hover:scale-110 transition duración-300">
            <span id="bd3Status" class="text-sm text-black px-3 py-1 rounded-full bg-gray-50 border border-2 border-gray-300 transition duración-300 flex items-center space-x-2 whitespace-nowrap">
                <div id="bd3Dot" class="w-2 h-2 rounded-full bg-gray-500 transition duración-300"></div>
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
                         bg-gray-50 border border-2 border-gray-300 transition duración-300
                         flex items-center space-x-2 whitespace-nowrap">
                <div id="baDot" class="w-2 h-2 rounded-full bg-gray-500 transition duración-300"></div>
                <span id="baText">BA: Off</span>
            </span>
        </div>

        {{-- TERMINOLOGÍA --}}
        <div class="absolute bottom-8 right-16 z-10 p-4 rounded text-xs">
            <img id="terminologiaImage" src="{{ asset('images/terminologia.jpeg') }}" alt="Terminología" class="w-96 h-auto border border-black">
        </div>

    </div>

    {{-- ========================================================
         MODAL DOSIFICACIÓN PERISTÁLTICAS (Automática / Manual)
       ======================================================== --}}
    <div id="doseModalOverlay"
         class="fixed inset-0 z-40 hidden items-center justify-center bg-black/50">
        <div
            class="w-full max-w-xl rounded-3xl shadow-2xl px-6 py-6 relative
                   bg-white text-slate-900
                   dark:bg-slate-900 dark:text-slate-50"
        >
            <h3 class="text-2xl font-semibold mb-5">
                Dosificar — <span id="doseDeviceName">Peristáltica</span>
            </h3>

            {{-- Tabs Automática / Manual --}}
            <div
                class="inline-flex mb-5 rounded-full bg-slate-100 dark:bg-slate-800
                       p-1 gap-1 w-full max-w-xs"
            >
                <button
                    id="doseTabAuto"
                    type="button"
                    class="flex-1 px-4 py-1.5 text-sm rounded-full font-medium
                           transition-colors"
                >
                    Automática
                </button>
                <button
                    id="doseTabManual"
                    type="button"
                    class="flex-1 px-4 py-1.5 text-sm rounded-full font-medium
                           transition-colors"
                >
                    Manual
                </button>
            </div>

            {{-- Sección Automática (solo info + cancelar) --}}
            <div id="doseAutoSection" class="space-y-4">
                <p id="doseAutoInfo"
                   class="text-sm text-slate-700 dark:text-slate-200">
                    Próxima dosis programada para lunes a las 10:00 a.m.
                </p>

                <div class="flex gap-3 pt-2">
                    <button
                        type="button"
                        class="px-5 py-2 rounded-full border
                               border-slate-300 text-sm text-slate-700
                               dark:border-slate-500 dark:text-slate-200
                               dose-cancel-btn"
                    >
                        Cancelar
                    </button>
                </div>
            </div>

            {{-- Sección Manual --}}
            <div id="doseManualSection" class="space-y-4 hidden">
                <label class="block text-sm font-medium">
                    Mililitros (ml)
                    <input
                        id="doseMlInput"
                        type="number"
                        min="0"
                        max="10"
                        class="mt-1 w-full rounded-2xl border px-3 py-2 text-sm
                               bg-slate-50 text-slate-900 border-slate-300
                               focus:outline-none focus:ring-2 focus:ring-cyan-500
                               dark:bg-slate-800 dark:text-slate-50
                               dark:border-slate-600"
                    />
                </label>

                {{-- Mensaje de error visual --}}
                <p id="doseMlError"
                   class="mt-1 text-xs text-red-600 dark:text-red-400 hidden">
                </p>

                <p class="text-xs text-slate-600 dark:text-slate-400">
                    Mientras se dosifica, esta ventana permanecerá bloqueada hasta terminar.
                </p>

                <div class="flex gap-3 pt-2">
                    <button
                        id="doseManualStart"
                        type="button"
                        class="px-5 py-2 rounded-full bg-cyan-500 text-white
                               font-semibold text-sm"
                    >
                        Dosificar ahora
                    </button>
                    <button
                        type="button"
                        class="px-5 py-2 rounded-full border
                               border-slate-300 text-sm text-slate-700
                               dark:border-slate-500 dark:text-slate-200
                               dose-cancel-btn"
                    >
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // =======================================================
            // CONSTANTES DE RUTAS / ESTADOS INICIALES
            // =======================================================
            const toggleUrl          = @json(route('scada.toggle'));
            const actuatorStates     = @json($actuatorStatesById ?? []);
            const statesUrl          = @json(route('scada.states'));
            const scadaBlockUrl      = @json(route('scada.block'));
            const manualDoseStartUrl = @json(route('scada.dose.manual.start'));
            const manualDoseStopUrl  = @json(route('scada.dose.manual.stop'));
            const csrfTokenMeta      = document.querySelector('meta[name="csrf-token"]');
            const csrfToken          = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

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
            const cameraFrame  = document.getElementById('cameraFrame');
            const cameraStatus = document.getElementById('cameraStatus');
            const cameraDot    = document.getElementById('cameraDot');
            const cameraText   = document.getElementById('cameraText');
            let   cameraIsOn   = false;

            function setCameraState(isOn) {
                cameraIsOn = isOn;

                if (!cameraStatus || !cameraDot || !cameraText) return;

                if (isOn) {
                    cameraText.textContent = 'Cámara: On';
                    cameraStatus.classList.remove(...statusClassesOff);
                    cameraStatus.classList.add(...statusClassesOn);
                    cameraDot.classList.remove(...dotClassesOff);
                    cameraDot.classList.add(...dotClassesOn);

                    // Habilitar hover / escala SOLO cuando hay imagen
                    if (cameraFrame) {
                        cameraFrame.classList.add('camera-online', 'cursor-pointer');
                    }
                } else {
                    cameraText.textContent = 'Cámara: Off';
                    cameraStatus.classList.remove(...statusClassesOn);
                    cameraStatus.classList.add(...statusClassesOff);
                    cameraDot.classList.remove(...dotClassesOn);
                    cameraDot.classList.add(...dotClassesOff);

                    if (cameraFrame) {
                        cameraFrame.classList.remove('camera-online', 'cursor-pointer');
                    }
                }
            }

            // Por default la dejamos en OFF
            setCameraState(false);

            if (cameraStream) {
                cameraStream.addEventListener('load', () => setCameraState(true));
                cameraStream.addEventListener('error', () => setCameraState(false));
            }

            // Click en la cámara → abrir nuevo tab SOLO si está ON
            if (cameraFrame && cameraStream) {
                cameraFrame.addEventListener('click', () => {
                    if (!cameraIsOn) return;
                    const url = cameraStream.getAttribute('src');
                    if (url) {
                        window.open(url, '_blank');
                    }
                });
            }

            // =======================================================
            // HELPER PARA PINTAR UN ACTUADOR
            // =======================================================
            function paintActuator(toggleEl, imageEl, dotEl, textEl, label, isOn, imageSrcOn = null, imageSrcOff = null, applyShake = true) {
                if (!toggleEl) return;

                const statusSpan = toggleEl.querySelector('span');

                if (textEl) {
                    textEl.textContent = `${label}: ${isOn ? 'On' : 'Off'}`;
                }

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
            // FUNCIÓN GENÉRICA DE ACTUADORES (BA, FAN)
            // =======================================================
            function setupActuatorToggle(
                toggleEl,
                imageEl,
                dotEl,
                textEl,
                label,
                isActuatorOnRef,
                imageSrcOn = null,
                imageSrcOff = null,
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
            // MODAL DE DOSIFICACIÓN PARA PERISTÁLTICAS
            // =======================================================
            const doseOverlay       = document.getElementById('doseModalOverlay');
            const doseDeviceNameEl  = document.getElementById('doseDeviceName');
            const doseTabAuto       = document.getElementById('doseTabAuto');
            const doseTabManual     = document.getElementById('doseTabManual');
            const doseAutoSection   = document.getElementById('doseAutoSection');
            const doseManualSection = document.getElementById('doseManualSection');
            const doseAutoInfo      = document.getElementById('doseAutoInfo');
            const doseMlInput       = document.getElementById('doseMlInput');
            const doseMlError       = document.getElementById('doseMlError');
            const doseManualStart   = document.getElementById('doseManualStart');
            const doseAutoStart     = document.getElementById('doseAutoStart'); // (probablemente null)
            const doseCancelButtons = document.querySelectorAll('.dose-cancel-btn');

            let currentDoseActuatorId = null;
            let doseBusy = false;
            let doseTimer = null;
            let doseErrorTimeout = null;

            function computeNextMondayText() {
                const now = new Date();
                const day = now.getDay(); // 0=Domingo, 1=Lunes...
                const targetHour = 10;
                const targetMinute = 0;

                let target = new Date(now);
                let labelToday = false;

                if (day === 1) {
                    const beforeTarget =
                        now.getHours() < targetHour ||
                        (now.getHours() === targetHour && now.getMinutes() < targetMinute);
                    if (beforeTarget) {
                        labelToday = true;
                    }
                }

                if (!labelToday) {
                    let diff = (1 - day + 7) % 7;
                    if (diff === 0) {
                        diff = 7; // lunes pero ya pasó la hora
                    }
                    target.setDate(now.getDate() + diff);
                }

                const dateStr = target.toLocaleDateString('es-MX', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                });

                if (labelToday) {
                    return 'Próxima dosis programada para hoy a las 10:00 a.m.';
                }
                return `Próxima dosis programada para ${dateStr} a las 10:00 a.m.`;
            }

            function selectDoseTab(mode) {
                const isAuto = (mode === 'auto');
                if (!doseTabAuto || !doseTabManual || !doseAutoSection || !doseManualSection) return;

                // Auto
                doseTabAuto.classList.toggle('bg-slate-700', isAuto);
                doseTabAuto.classList.toggle('text-slate-50', isAuto);
                doseTabAuto.classList.toggle('text-slate-300', !isAuto);

                // Manual
                doseTabManual.classList.toggle('bg-slate-700', !isAuto);
                doseTabManual.classList.toggle('text-slate-50', !isAuto);
                doseTabManual.classList.toggle('text-slate-300', isAuto);

                doseAutoSection.classList.toggle('hidden', !isAuto);
                doseManualSection.classList.toggle('hidden', isAuto);
            }

            function clearDoseMlError() {
                if (!doseMlInput || !doseMlError) return;

                doseMlInput.classList.remove(
                    'border-red-500',
                    'bg-red-50',
                    'dark:border-red-500',
                    'dark:bg-red-950/30'
                );
                doseMlError.classList.add('hidden');
                doseMlError.textContent = '';

                if (doseErrorTimeout) {
                    clearTimeout(doseErrorTimeout);
                    doseErrorTimeout = null;
                }
            }

            function showDoseMlError(message) {
                if (!doseMlInput || !doseMlError) return;

                clearDoseMlError();

                doseMlInput.classList.add(
                    'border-red-500',
                    'bg-red-50',
                    'dark:border-red-500',
                    'dark:bg-red-950/30'
                );
                doseMlError.textContent = message;
                doseMlError.classList.remove('hidden');

                doseErrorTimeout = setTimeout(() => {
                    clearDoseMlError();
                }, 3000);
            }

            function setDoseBusy(isBusy) {
                doseBusy = isBusy;

                if (doseManualStart) doseManualStart.disabled = isBusy;
                if (doseAutoStart)   doseAutoStart.disabled   = isBusy;
                if (doseMlInput)     doseMlInput.readOnly     = isBusy;

                doseCancelButtons.forEach(btn => {
                    btn.disabled = isBusy;
                    if (isBusy) {
                        btn.classList.add('opacity-60', 'cursor-not-allowed');
                    } else {
                        btn.classList.remove('opacity-60', 'cursor-not-allowed');
                    }
                });
            }

            function openDoseModal(actuatorId, label) {
                currentDoseActuatorId = actuatorId;

                if (doseDeviceNameEl) {
                    doseDeviceNameEl.textContent = label || 'Peristáltica';
                }
                if (doseMlInput) {
                    doseMlInput.value = '0';
                }
                clearDoseMlError();

                if (doseAutoInfo) {
                    doseAutoInfo.textContent = computeNextMondayText();
                }

                selectDoseTab('auto');
                setDoseBusy(false);

                if (doseOverlay) {
                    doseOverlay.classList.remove('hidden');
                    doseOverlay.classList.add('flex');
                }
            }

            function closeDoseModal() {
                if (doseBusy) return;
                currentDoseActuatorId = null;
                clearDoseMlError();
                if (doseTimer) {
                    clearTimeout(doseTimer);
                    doseTimer = null;
                }
                if (doseOverlay) {
                    doseOverlay.classList.add('hidden');
                    doseOverlay.classList.remove('flex');
                }
            }

            if (doseOverlay) {
                doseOverlay.addEventListener('click', (event) => {
                    if (event.target === doseOverlay) {
                        closeDoseModal();
                    }
                });
            }

            if (doseTabAuto && doseTabManual) {
                doseTabAuto.addEventListener('click', () => {
                    if (!doseBusy) selectDoseTab('auto');
                });
                doseTabManual.addEventListener('click', () => {
                    if (!doseBusy) selectDoseTab('manual');
                });
            }

            doseCancelButtons.forEach(btn => {
                btn.addEventListener('click', () => closeDoseModal());
            });

            if (doseManualStart) {
                doseManualStart.addEventListener('click', async () => {
                    if (doseBusy) return;
                    if (!currentDoseActuatorId || !manualDoseStartUrl) return;

                    const raw = doseMlInput?.value ?? '0';
                    const ml  = parseInt(raw, 10);

                    if (!Number.isFinite(ml) || ml <= 0) {
                        showDoseMlError('Ingresa una cantidad mayor a 0 ml.');
                        return;
                    }
                    if (ml > 10) {
                        showDoseMlError('No se pueden suministrar más de 10 ml en una sola dosis.');
                        return;
                    }

                    clearDoseMlError();
                    setDoseBusy(true);

                    try {
                        const resp = await fetch(manualDoseStartUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                id_actuador: currentDoseActuatorId,
                                ml: ml,
                            }),
                        });

                        if (!resp.ok) {
                            console.error('Error HTTP al iniciar dosis manual', resp.status);
                            setDoseBusy(false);
                            return;
                        }

                        const json = await resp.json();
                        if (!json.ok) {
                            console.error('Respuesta no OK al iniciar dosis manual', json);
                            setDoseBusy(false);
                            return;
                        }

                        const durationSec = typeof json.duration === 'number' ? json.duration : 0;

                        if (typeof refreshActuatorStates === 'function') {
                            refreshActuatorStates();
                        }

                        if (durationSec > 0 && manualDoseStopUrl) {
                            doseTimer = setTimeout(async () => {
                                try {
                                    await fetch(manualDoseStopUrl, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'X-CSRF-TOKEN': csrfToken,
                                        },
                                        body: JSON.stringify({
                                            id_actuador: currentDoseActuatorId,
                                        }),
                                    });
                                } catch (e) {
                                    console.error('Error al finalizar dosis manual', e);
                                } finally {
                                    setDoseBusy(false);
                                    closeDoseModal();
                                    if (typeof refreshActuatorStates === 'function') {
                                        refreshActuatorStates();
                                    }
                                }
                            }, durationSec * 1000);
                        } else {
                            setDoseBusy(false);
                            closeDoseModal();
                        }
                    } catch (e) {
                        console.error('Exception iniciando dosis manual', e);
                        setDoseBusy(false);
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
            // CONFIGURACIÓN DE ACTUADORES (solo BA y FAN)
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

            setupActuatorToggle(
                baToggle,
                baImage,
                baDot,
                baText,
                'BA',
                isBAOn
            );

            // Los clics de BD1/BD2/BD3 abren el modal de dosificación
            function labelForDoser(actuatorId) {
                const idNum = parseInt(actuatorId, 10);
                if (idNum === 2) return 'Peristáltica A — FloraMicro';
                if (idNum === 3) return 'Peristáltica B — FloraBloom';
                if (idNum === 1) return 'Peristáltica C — FloraGro';
                return 'Peristáltica';
            }

            function attachDoseClick(toggleEl) {
                if (!toggleEl) return;
                const actuatorId = toggleEl.dataset.actuatorId;
                if (!actuatorId) return;

                toggleEl.addEventListener('click', () => {
                    openDoseModal(parseInt(actuatorId, 10), labelForDoser(actuatorId));
                });
            }

            attachDoseClick(bd1Toggle);
            attachDoseClick(bd2Toggle);
            attachDoseClick(bd3Toggle);

            // =======================================================
            // REFRESCO PERIÓDICO DE SENSORES (cada 10s, HTML Blade)
            // =======================================================
            const scadaSensorsContainer = document.getElementById('scada-sensors');

            async function refreshSensors() {
                if (!scadaSensorsContainer || !scadaBlockUrl) return;

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

                    // BD1 = id 2
                    if (data['2'] !== undefined) {
                        const on = data['2'] === 1;
                        isBD1On.value = on;
                        paintActuator(bd1Toggle, bd1Image, bd1Dot, bd1Text, 'BD1', on);
                    }
                    // BD2 = id 3 (Bloom)
                    if (data['3'] !== undefined) {
                        const on = data['3'] === 1;
                        isBD2On.value = on;
                        paintActuator(bd2Toggle, bd2Image, bd2Dot, bd2Text, 'BD2', on);
                    }
                    // BD3 = id 1 (Gro)
                    if (data['1'] !== undefined) {
                        const on = data['1'] === 1;
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

            // Timers iniciales
            refreshSensors();
            refreshActuatorStates();

            setInterval(refreshSensors, 10000);       // sensores cada 10s
            setInterval(refreshActuatorStates, 5000); // actuadores cada 5s
        </script>
    @endpush

    @push('styles')
        <style>
            :root {
                --top-offset: 0px;
                --image-lift: 35px;
                --scada-shift-x: -120px;
            }

            .bg-container {
                background-image: url('{{ asset('images/Fondo_W.png') }}');
                margin-top: 0;
                min-height: calc(100vh + 70px);
                position: relative;
                width: 100%;
                background-size: 100% auto;
                background-position: calc(50% - 5px) calc(50% - var(--image-lift));
                background-repeat: no-repeat;

                /* dejamos que el contenido se pueda salir un poco */
                overflow: visible;
                transform: translateX(var(--scada-shift-x));
            }

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

            /* ===========================
               Cámara: ESCALAR EL RECUADRO
               =========================== */
            .camera-frame {
                position: relative;
                overflow: hidden;
                transform-origin: center center; /* escala sobre su propio eje */
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            .camera-frame img {
                display: block;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            /* solo cuando está online se permite el efecto */
            .camera-frame.camera-online:hover {
                transform: scale(1.25);
                box-shadow: 0 20px 40px rgba(0,0,0,0.35);
                z-index: 40; /* por si se monta sobre otros elementos */
            }
        </style>
    @endpush
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Hortalizas') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 bg-gray-50 dark:bg-gray-900 min-h-screen text-gray-800 dark:text-gray-200 transition-colors duration-200">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h3 class="text-2xl font-semibold text-center sm:text-left">Gestión de Hortalizas</h3>
            <button id="openModal"
                class="w-full sm:w-auto bg-teal-500 hover:bg-teal-400 text-white px-4 py-2 rounded-lg shadow-md transition text-center">
                Cambiar de cultivo
            </button>
        </div>

        <div class="space-y-3">
            @foreach($hortalizas as $hortaliza)
                @php
                    $icons = [
                        'lechuga' => 'lechuga.png',
                        'espinaca' => 'espinaca.png',
                        'acelga' => 'acelga.png',
                        'rucula' => 'rucula.png',
                        'albahaca' => 'albahaca.png',
                        'mostaza' => 'mostaza.png',
                    ];
                    $nombre = strtolower($hortaliza->nombre);
                    $iconPath = asset('images/' . ($icons[$nombre] ?? 'lechuga.png'));
                @endphp

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 rounded-xl px-4 sm:px-5 py-4 border transition-colors duration-200
                    bg-white/70 dark:bg-gray-800
                    {{ $hortaliza->seleccion ? 'border-teal-400' : 'border-gray-300 dark:border-gray-700 hover:border-gray-500 dark:hover:border-gray-400' }}">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $iconPath }}" alt="{{ $hortaliza->nombre }}" class="w-8 h-8 object-contain">
                        <span class="text-lg">{{ ucfirst($hortaliza->nombre) }}</span>
                    </div>
                    @if($hortaliza->seleccion)
                        <span class="text-teal-500 font-semibold sm:text-right">Seleccionada</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    <div id="modal"
         class="hidden fixed inset-0 bg-black/60 flex justify-center items-center z-50 p-4 transition-opacity duration-200">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl w-full max-w-sm shadow-xl transform transition-all scale-95 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Selecciona tu cultivo</h4>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">✕</button>
            </div>

            <form method="POST" action="{{ route('hortalizas.cambiar') }}">
                @csrf
                <div class="space-y-3">
                    @foreach($hortalizas as $hortaliza)
                        @php
                            $nombre = strtolower($hortaliza->nombre);
                            $iconPath = asset('images/' . ($icons[$nombre] ?? 'lechuga.png'));
                        @endphp
                        <button name="id_hortaliza" value="{{ $hortaliza->id_hortaliza }}"
                            class="w-full flex items-center space-x-3 px-4 py-2 rounded-lg transition text-left
                            {{ $hortaliza->seleccion 
                                ? 'bg-teal-500 text-white' 
                                : 'bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200' }}">
                            <img src="{{ $iconPath }}" alt="{{ $hortaliza->nombre }}" class="w-6 h-6 object-contain">
                            <span>{{ ucfirst($hortaliza->nombre) }}</span>
                        </button>
                    @endforeach
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal');
        const openModal = document.getElementById('openModal');
        const closeModal = document.getElementById('closeModal');

        openModal.addEventListener('click', () => modal.classList.remove('hidden'));
        closeModal.addEventListener('click', () => modal.classList.add('hidden'));
        window.addEventListener('click', e => { if (e.target === modal) modal.classList.add('hidden'); });
    </script>
</x-app-layout>

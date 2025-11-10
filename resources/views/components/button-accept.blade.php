@props([
    'action',                 // URL del endpoint (obligatorio)
    'method' => 'PUT',        // PUT por defecto (aceptar/activar)
    'itemId' => null,
    'tituloModal' => 'Confirmar',
    'messageAlert' => '¿Confirmar?',
])

<div x-data="{ open: false }" @modal:close.stop="open = false" x-id="['modal']">
    <button @click="open = true"
            class="bg-green-200 p-2 ml-2 rounded-full transition duration-300 ease-in-out hover:bg-green-300"
            type="button" title="Aceptar/Activar">
        <i class='bx bx-check-circle text-2xl text-green-600'></i>
    </button>

    <div x-show="open" x-cloak x-transition
         class="fixed inset-0 bg-gray-800/50 flex justify-center items-center"
         @keydown.escape.window="open = false" @click.self="open = false"
         role="dialog" :aria-labelledby="$id('modal')" style="display:none;">
        <div class="bg-white rounded-3xl p-8 w-[800px] max-h-[80vh] shadow-lg overflow-y-auto" @click.stop>
            <h2 :id="$id('modal')" class="text-2xl font-bold mb-6 text-center text-[#2A334B]">
                {{ $tituloModal }}
            </h2>

            <form x-on:submit.prevent class="space-y-4 text-black ajax-form" action="{{ $action }}" method="POST">
                @csrf
                @if (strtoupper($method) !== 'POST')
                    @method($method)
                @endif

                @if ($itemId)
                    <input type="hidden" name="id" value="{{ $itemId }}">
                @endif

                <p class="text-center text-xl"><b>{!! $messageAlert !!}</b></p>

                <div class="flex justify-end mt-6">
                    <button type="button" @click="open = false"
                            class="bg-gray-300 text-gray-700 py-2 px-4 rounded-full flex items-center mr-4 hover:bg-gray-400">
                        <i class='bx bx-x text-xl text-gray-700'></i><span>Cancelar</span>
                    </button>

                    <button type="submit"
                            class="bg-green-300 text-gray-700 py-2 px-4 rounded-full flex items-center hover:bg-green-400">
                        <i class='bx bx-check-circle text-xl text-gray-700'></i><span>&nbsp;Confirmar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
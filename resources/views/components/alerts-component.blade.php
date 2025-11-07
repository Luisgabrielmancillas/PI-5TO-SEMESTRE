@php
    // Lista de posibles tipos de mensaje
    $flashTypes = ['success', 'error', 'info', 'warning'];
@endphp

<!-- Contenedor de las alertas -->
<div id="alert-container" class="fixed top-4 right-4 flex flex-col space-y-4 z-50">
    <!-- Mostrar errores de validación clásicos -->
    @if ($errors->any())
        <div
            class="alert w-80 p-4 rounded-lg shadow-lg relative bg-red-100 border border-red-400 text-red-700 animate-fade-in">
            <button class="close-alert absolute top-2 right-2 text-lg font-bold text-gray-500 hover:text-gray-700">
                &times;
            </button>
            <div class="font-bold text-lg mb-1">Errores de validación:</div>
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Mostrar mensajes flash -->
    @foreach ($flashTypes as $type)
        @if (session()->has($type))
            <div
                class="alert w-80 p-4 rounded-lg shadow-lg relative
                @if ($type === 'success') bg-green-100 border border-green-400 text-green-700
                @elseif($type === 'info') bg-blue-100 border border-blue-400 text-blue-700
                @elseif($type === 'warning') bg-yellow-100 border border-yellow-400 text-yellow-700
                @elseif($type === 'error') bg-red-100 border border-red-400 text-red-700 @endif animate-fade-in">

                <button class="close-alert absolute top-2 right-2 text-lg font-bold text-gray-500 hover:text-gray-700">
                    &times;
                </button>

                <p>{{ session($type) }}</p>
            </div>
        @endif
    @endforeach
</div>

<!-- Contenedor dinámico para errores de respuestas JSON -->
<div id="json-errors-container" class="fixed top-4 right-4 flex flex-col space-y-4 z-50"></div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease forwards;
    }

    .animate-fade-out {
        animation: fadeOut 0.5s ease forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración de las alertas flash
        setTimeout(function() {
            document.querySelectorAll('#alert-container .alert').forEach(function(alert) {
                alert.classList.add('animate-fade-out');
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000); // Eliminar alertas después de 5 segundos

        // Botón para cerrar manualmente las alertas
        document.querySelectorAll('.close-alert').forEach(function(button) {
            button.addEventListener('click', function() {
                let alert = button.parentElement;
                // Eliminar el primer mensaje
                const container = document.getElementById('alert-container');
                const firstAlert = container.querySelector('.alert'); // Primer alerta
                if (firstAlert) {
                    firstAlert.classList.add('animate-fade-out');
                    setTimeout(() => firstAlert.remove(), 500);
                }

                alert.classList.add('animate-fade-out');
                setTimeout(() => alert.remove(), 500); // Animación de fade-out
            });
        });
    });

   
</script>

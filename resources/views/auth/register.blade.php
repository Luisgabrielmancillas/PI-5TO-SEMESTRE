<x-guest-layout>
    <!-- Encabezado -->
    <header class="mb-6 text-center">
        <h1 class="text-2xl font-semibold tracking-tight">Crea tu cuenta</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Regístrate para comenzar</p>
    </header>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <div class="mt-1">
                <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Tu nombre" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="mt-1">
                <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="tu@correo.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative mt-1">
                <x-text-input
                    id="password"
                    class="block w-full pr-11"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••" />
                <button type="button"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                        aria-label="Mostrar u ocultar contraseña"
                        data-toggle-password="#password">
                    <!-- eye -->
                    <svg data-eye xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="2" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3" stroke-width="2"/>
                    </svg>
                    <!-- eye-off -->
                    <svg data-eye-off xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="2" d="M3 3l18 18M10.6 10.6a3 3 0 104.2 4.2M9.88 4.25A10.9 10.9 0 0112 4c6.5 0 10 7 10 7a18 18 0 01-4.23 5.18M6.1 6.1A18 18 0 002 11s3.5 7 10 7c1.9 0 3.65-.5 5.2-1.32"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative mt-1">
                <x-text-input
                    id="password_confirmation"
                    class="block w-full pr-11"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••" />
                <button type="button"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                        aria-label="Mostrar u ocultar contraseña"
                        data-toggle-password="#password_confirmation">
                    <!-- eye -->
                    <svg data-eye xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="2" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3" stroke-width="2"/>
                    </svg>
                    <!-- eye-off -->
                    <svg data-eye-off xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="2" d="M3 3l18 18M10.6 10.6a3 3 0 104.2 4.2M9.88 4.25A10.9 10.9 0 0112 4c6.5 0 10 7 10 7a18 18 0 01-4.23 5.18M6.1 6.1A18 18 0 002 11s3.5 7 10 7c1.9 0 3.65-.5 5.2-1.32"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- CTA -->
        <x-primary-button class="w-full justify-center">
            {{ __('Continuar') }}
        </x-primary-button>

        <!-- Divider + link a login -->
        <div class="text-center text-sm text-gray-500 dark:text-gray-400">
            <span>¿Ya tienes cuenta?</span>
            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                Inicia sesión
            </a>
        </div>
    </form>
</x-guest-layout>
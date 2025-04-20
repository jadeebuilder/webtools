<x-auth-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Log in to WebTools') }}</h1>
        <p class="mt-2 text-sm text-gray-600">{{ __('Access all your favorite tools in one place') }}</p>
    </div>
    
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login', ['locale' => app()->getLocale()]) }}">
        @csrf

        <!-- Champ caché pour la redirection après connexion -->
        @if(isset($redirectTo))
            <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">
        @endif

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Adresse email')" />
            <div class="flex">
                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md">
                    <i class="fas fa-envelope text-gray-500"></i>
                </span>
                <x-text-input id="email" class="block mt-0 w-full rounded-none rounded-e-md" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div class="flex">
                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md">
                    <i class="fas fa-lock text-gray-500"></i>
                </span>
                <x-text-input id="password" class="block mt-0 w-full rounded-none rounded-e-md"
                              type="password"
                              name="password"
                              required autocomplete="current-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mt-4 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
            
            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 hover:text-purple-500 transition duration-150" href="{{ route('password.request', ['locale' => app()->getLocale()]) }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full flex justify-center py-3 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
        
        <div class="mt-6 text-center text-sm">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="text-purple-500 hover:text-purple-700 font-medium">
                {{ __('Register now') }}
            </a>
        </div>
    </form>
</x-auth-layout>

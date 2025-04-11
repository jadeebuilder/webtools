<x-auth-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Create your WebTools account') }}</h1>
        <p class="mt-2 text-sm text-gray-600">{{ __('Join thousands of users who use our tools daily') }}</p>
    </div>
    
    <form method="POST" action="{{ route('register', ['locale' => app()->getLocale()]) }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Firstname -->
            <div>
                <x-input-label for="firstname" :value="__('First Name')" />
                <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname')" required autofocus />
                <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
            </div>

            <!-- Lastname -->
            <div>
                <x-input-label for="lastname" :value="__('Last Name')" />
                <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" required />
                <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
            </div>
        </div>
        
        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-phone-input id="phone" class="block w-full" type="tel" name="phone" :value="old('phone')" required />
            <div id="phone-error" class="mt-1 text-sm text-red-600 hidden"></div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <div class="flex">
                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md">
                    <i class="fas fa-envelope text-gray-500"></i>
                </span>
                <x-text-input id="email" class="block mt-0 w-full rounded-none rounded-e-md" type="email" name="email" :value="old('email')" required />
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
                              required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="flex">
                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md">
                    <i class="fas fa-lock text-gray-500"></i>
                </span>
                <x-text-input id="password_confirmation" class="block mt-0 w-full rounded-none rounded-e-md"
                              type="password"
                              name="password_confirmation" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        
        <!-- Newsletter Subscription -->
        <div class="mt-4">
            <label for="newsletter_subscribed" class="inline-flex items-center">
                <input id="newsletter_subscribed" type="checkbox" class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500" name="newsletter_subscribed" value="1" {{ old('newsletter_subscribed') ? 'checked' : '' }}>
                <span class="ms-2 text-sm text-gray-600">{{ __('Subscribe to our newsletter for updates and special offers') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-gray-600 hover:text-purple-500 transition duration-150" href="{{ route('login', ['locale' => app()->getLocale()]) }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700">
                {{ __('Register') }}
            </x-primary-button>
        </div>
        
        <div class="mt-6 text-center text-xs text-gray-500">
            {{ __('By registering, you agree to our') }} 
            <a href="#" class="text-purple-500 hover:text-purple-700">{{ __('Terms of Service') }}</a> 
            {{ __('and') }} 
            <a href="#" class="text-purple-500 hover:text-purple-700">{{ __('Privacy Policy') }}</a>
        </div>
    </form>
</x-auth-layout>


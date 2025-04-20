<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-8">
                    <!-- Header -->
                    <div class="text-center">
                        <h1 class="text-3xl font-extrabold text-gray-900">{{ __('Commencer votre période d\'essai gratuite') }}</h1>
                        <p class="mt-4 text-lg text-gray-600">{{ __('Vous êtes sur le point d\'activer votre période d\'essai gratuite de :days jours pour le forfait', ['days' => $trial_days]) }}</p>
                        <div class="mt-2 text-2xl font-bold text-purple-600">{{ $package->getName() }}</div>
                    </div>
                    
                    <!-- Package details -->
                    <div class="mt-8 bg-purple-50 border border-purple-100 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="text-purple-600 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">{{ __('Détails de l\'offre d\'essai') }}</h2>
                        </div>
                        
                        <div class="ml-11 space-y-3">
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-gray-700">{{ __('Accès complet à toutes les fonctionnalités pendant :days jours', ['days' => $trial_days]) }}</p>
                            </div>
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-gray-700">{{ __('Aucune information de paiement demandée') }}</p>
                            </div>
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-gray-700">{{ __('Annulation possible à tout moment') }}</p>
                            </div>
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-gray-700">{{ __('Vous testez le forfait avec cycle') }} 
                                        <span class="font-medium">
                                            @if($cycle == 'monthly')
                                                {{ __('mensuel') }}
                                            @elseif($cycle == 'annual')
                                                {{ __('annuel') }}
                                            @endif
                                        </span>
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">{{ __('À la fin de votre période d\'essai, vous pourrez choisir de vous abonner ou votre compte reviendra automatiquement à la version gratuite.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Features -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Fonctionnalités incluses') }}</h3>
                        
                        <div class="grid md:grid-cols-2 gap-3">
                            @php
                                $features = explode("\n", $package->getFeatures());
                            @endphp
                            
                            @foreach($features as $feature)
                                @if(!empty(trim($feature)))
                                    <div class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500 mr-2 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">{{ $feature }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Call to action -->
                    <div class="mt-8 text-center">
                        <form action="{{ route('trial.activate', ['locale' => app()->getLocale(), 'slug' => $package->slug]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="cycle" value="{{ $cycle }}">
                            
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                {{ __('Activer ma période d\'essai gratuite') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>
                        
                        <div class="mt-4">
                            <a href="{{ route('checkout', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => $cycle]) }}" class="text-sm text-purple-600 hover:text-purple-500">
                                {{ __('Je préfère souscrire directement sans période d\'essai') }}
                            </a>
                        </div>
                    </div>
                    
                    <!-- Disclaimer -->
                    <div class="mt-6 text-center text-sm text-gray-500">
                        <p>{{ __('En activant votre période d\'essai, vous acceptez nos') }} <a href="{{ route('terms', ['locale' => app()->getLocale()]) }}" class="text-purple-600 hover:text-purple-500">{{ __('conditions d\'utilisation') }}</a> {{ __('et notre') }} <a href="{{ route('privacy', ['locale' => app()->getLocale()]) }}" class="text-purple-600 hover:text-purple-500">{{ __('politique de confidentialité') }}</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
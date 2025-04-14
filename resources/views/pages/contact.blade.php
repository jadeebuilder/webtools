<x-app-layout>
    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Contactez-nous') }}</h1>
            <p class="text-lg text-gray-600">{{ __('Nous sommes là pour vous aider. N\'hésitez pas à nous contacter.') }}</p>
        </div>

        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-8">
            <form class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Nom') }}</label>
                    <input type="text" id="name" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700">{{ __('Sujet') }}</label>
                    <input type="text" id="subject" name="subject" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">{{ __('Message') }}</label>
                    <textarea id="message" name="message" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"></textarea>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg inline-block transition-colors duration-300">
                        <i class="fas fa-paper-plane mr-2"></i>{{ __('Envoyer le message') }}
                    </button>
                </div>
            </form>
        </div>
        
        <div class="max-w-4xl mx-auto mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-xl text-primary"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-900 mb-2">{{ __('Email') }}</h3>
                <p class="text-gray-600">contact@webtools.com</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-phone text-xl text-primary"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-900 mb-2">{{ __('Téléphone') }}</h3>
                <p class="text-gray-600">+33 1 23 45 67 89</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-xl text-primary"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-900 mb-2">{{ __('Adresse') }}</h3>
                <p class="text-gray-600">123 Rue des Développeurs<br>75000 Paris, France</p>
            </div>
        </div>
    </div>
</x-app-layout> 
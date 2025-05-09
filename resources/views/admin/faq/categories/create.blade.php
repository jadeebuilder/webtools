<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Ajouter une catégorie de FAQ') }}</h1>
            <a href="{{ route('admin.faq.categories.index', ['locale' => app()->getLocale()]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('Retour à la liste') }}
            </a>
        </div>

        @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden p-6">
            <form action="{{ route('admin.faq.categories.store', ['locale' => app()->getLocale()]) }}" method="POST">
                @csrf
                
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">{{ __('Informations principales') }}</h2>
                    
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Nom') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Description') }}</label>
                        <textarea name="description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="icon" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Icône (classe FontAwesome)') }}</label>
                        <input type="text" name="icon" id="icon" value="{{ old('icon') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="fas fa-question-circle">
                        <p class="text-sm text-gray-500 mt-1">{{ __('Exemple : fas fa-question-circle') }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="order" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Ordre') }}</label>
                        <input type="number" name="order" id="order" value="{{ old('order', 0) }}" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" class="mr-2" {{ old('is_active') ? 'checked' : 'checked' }}>
                            <label for="is_active" class="text-gray-700">{{ __('Actif') }}</label>
                        </div>
                    </div>
                </div>
                
                <!-- Traductions -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">{{ __('Traductions') }}</h2>
                    
                    <div x-data="{ activeTab: 'none' }" class="mb-4">
                        <!-- Onglets de langue -->
                        <div class="flex border-b mb-4">
                            <button type="button" @click="activeTab = 'none'" :class="activeTab === 'none' ? 'border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-2 focus:outline-none">
                                {{ __('Aucune') }}
                            </button>
                            
                            @foreach($languages as $language)
                                <button type="button" @click="activeTab = '{{ $language->code }}'" :class="activeTab === '{{ $language->code }}' ? 'border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-2 focus:outline-none">
                                    {{ $language->name }}
                                </button>
                            @endforeach
                        </div>
                        
                        <!-- Contenu des onglets -->
                        @foreach($languages as $language)
                            <div x-show="activeTab === '{{ $language->code }}'" class="border rounded-lg p-4 bg-gray-50">
                                <input type="hidden" name="translations[{{ $language->code }}][language_id]" value="{{ $language->id }}">
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Nom') }} ({{ $language->name }}) <span class="text-red-500">*</span></label>
                                    <input type="text" name="translations[{{ $language->code }}][name]" value="{{ old('translations.' . $language->code . '.name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('Description') }} ({{ $language->name }})</label>
                                    <textarea name="translations[{{ $language->code }}][description]" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('translations.' . $language->code . '.description') }}</textarea>
                                </div>
                            </div>
                        @endforeach
                        
                        <div x-show="activeTab === 'none'" class="border rounded-lg p-4 bg-gray-50 text-center py-8">
                            <p class="text-gray-500">{{ __('Sélectionnez une langue pour ajouter une traduction') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors duration-300">
                        <i class="fas fa-save mr-2"></i>{{ __('Enregistrer') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout> 
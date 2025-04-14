<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Ajouter un témoignage') }}</h1>
            <a href="{{ route('admin.testimonials.index', ['locale' => app()->getLocale()]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-300">
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
            <form action="{{ route('admin.testimonials.store', ['locale' => app()->getLocale()]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">{{ __('Informations principales') }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Nom') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   required>
                        </div>
                        
                        <div>
                            <label for="position" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Poste') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="position" 
                                   id="position" 
                                   value="{{ old('position') }}" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   required>
                        </div>
                        
                        <div>
                            <label for="rating" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Évaluation') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="rating" 
                                    id="rating" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required>
                                <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 {{ __('étoiles') }}</option>
                                <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 {{ __('étoiles') }}</option>
                                <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 {{ __('étoiles') }}</option>
                                <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 {{ __('étoiles') }}</option>
                                <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 {{ __('étoile') }}</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="order" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Ordre d\'affichage') }}
                            </label>
                            <input type="number" 
                                   name="order" 
                                   id="order" 
                                   value="{{ old('order', 0) }}" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        
                        <div>
                            <label for="avatar" class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('Avatar') }}
                            </label>
                            <input type="file" 
                                   name="avatar" 
                                   id="avatar" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   accept="image/*">
                            <p class="text-sm text-gray-500 mt-1">{{ __('Image au format JPG, PNG ou GIF (max 2MB)') }}</p>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   class="mr-2" 
                                   {{ old('is_active') ? 'checked' : 'checked' }}>
                            <label for="is_active" class="text-gray-700">{{ __('Actif') }}</label>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="content" class="block text-gray-700 text-sm font-bold mb-2">
                            {{ __('Contenu du témoignage') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea name="content" 
                                  id="content" 
                                  rows="4" 
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                  required>{{ old('content') }}</textarea>
                    </div>
                </div>
                
                <!-- Traductions -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">{{ __('Traductions') }}</h2>
                    
                    @if(isset($languages) && $languages->count() > 0)
                        <div x-data="{ activeTab: '{{ $languages->first()->id }}' }" class="mb-4">
                            <!-- Onglets de langue -->
                            <div class="flex border-b mb-4 overflow-x-auto">
                                @foreach($languages as $language)
                                    <button type="button" 
                                            @click="activeTab = '{{ $language->id }}'" 
                                            :class="activeTab === '{{ $language->id }}' ? 'border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-gray-700'" 
                                            class="px-4 py-2 focus:outline-none whitespace-nowrap">
                                        @if($language->is_default ?? false)
                                            <i class="fas fa-star text-yellow-400 mr-1" title="{{ __('Langue par défaut') }}"></i>
                                        @endif
                                        {{ $language->name }}
                                    </button>
                                @endforeach
                            </div>
                            
                            <!-- Contenu des onglets -->
                            @foreach($languages as $language)
                                <div x-show="activeTab === '{{ $language->id }}'" class="border rounded-lg p-4 bg-gray-50">
                                    <input type="hidden" name="translations[{{ $language->id }}][language_id]" value="{{ $language->id }}">
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                                {{ __('Nom') }}
                                            </label>
                                            <input type="text" 
                                                name="translations[{{ $language->id }}][name]" 
                                                value="{{ old('translations.' . $language->id . '.name') }}" 
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                                {{ __('Poste') }}
                                            </label>
                                            <input type="text" 
                                                name="translations[{{ $language->id }}][position]" 
                                                value="{{ old('translations.' . $language->id . '.position') }}" 
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                            {{ __('Contenu du témoignage') }}
                                        </label>
                                        <textarea name="translations[{{ $language->id }}][content]" 
                                                rows="4" 
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('translations.' . $language->id . '.content') }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 bg-yellow-100 text-yellow-700 rounded-lg">
                            {{ __('Les langues ne sont pas disponibles actuellement. Vous pourrez ajouter des traductions ultérieurement.') }}
                        </div>
                    @endif
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
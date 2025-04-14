<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Modifier une FAQ') }}</h1>
            <a href="{{ route('admin.faq.index', ['locale' => app()->getLocale()]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-300">
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
            <form action="{{ route('admin.faq.update', ['locale' => app()->getLocale(), 'faq' => $faq->id]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">{{ __('Informations principales') }}</h2>
                    
                    <div class="mb-4">
                        <label for="faq_category_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Catégorie') }} <span class="text-red-500">*</span></label>
                        <select name="faq_category_id" id="faq_category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">{{ __('Sélectionner une catégorie') }}</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('faq_category_id', $faq->faq_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="order" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Ordre') }}</label>
                        <input type="number" name="order" id="order" value="{{ old('order', $faq->order) }}" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1" class="mr-2" {{ old('is_active', $faq->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <label for="is_active" class="text-gray-700">{{ __('Actif') }}</label>
                        </div>
                    </div>
                </div>

                <!-- Traductions -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">{{ __('Traductions') }}</h2>
                    
                    <div x-data="{ activeTab: '{{ $languages->first()->id }}' }" class="mb-4">
                        <!-- Onglets de langue -->
                        <div class="flex border-b mb-4 overflow-x-auto">
                            @foreach($languages as $language)
                                <button type="button" 
                                        @click="activeTab = '{{ $language->id }}'" 
                                        :class="activeTab === '{{ $language->id }}' ? 'border-b-2 border-primary text-primary' : 'text-gray-500 hover:text-gray-700'" 
                                        class="px-4 py-2 focus:outline-none whitespace-nowrap">
                                    @if($language->is_default)
                                        <i class="fas fa-star text-yellow-400 mr-1" title="{{ __('Langue par défaut') }}"></i>
                                    @endif
                                    {{ $language->name }}
                                    @if(isset($translations[$language->id]))
                                        <i class="fas fa-check text-green-500 ml-1" title="{{ __('Traduction existante') }}"></i>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                        
                        <!-- Contenu des onglets -->
                        @foreach($languages as $language)
                            <div x-show="activeTab === '{{ $language->id }}'" class="border rounded-lg p-4 bg-gray-50">
                                <input type="hidden" name="translations[{{ $language->id }}][language_id]" value="{{ $language->id }}">
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        {{ __('Question') }} 
                                        @if($language->is_default)<span class="text-red-500">*</span>@endif
                                    </label>
                                    <input type="text" 
                                           name="translations[{{ $language->id }}][question]" 
                                           value="{{ old('translations.' . $language->id . '.question', isset($translations[$language->id]) ? $translations[$language->id]->question : '') }}" 
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           @if($language->is_default) required @endif>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        {{ __('Réponse') }}
                                        @if($language->is_default)<span class="text-red-500">*</span>@endif
                                    </label>
                                    <textarea name="translations[{{ $language->id }}][answer]" 
                                              rows="5" 
                                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                              @if($language->is_default) required @endif>{{ old('translations.' . $language->id . '.answer', isset($translations[$language->id]) ? $translations[$language->id]->answer : '') }}</textarea>
                                </div>
                            </div>
                        @endforeach
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
<x-app-layout :pageTitle="__('Modifier une publicité')" :metaDescription="__('Modifier une publicité existante')">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Modifier une publicité') }}
            </h1>
            <a href="{{ route('admin.ads.index', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.ads.update', ['ad' => $ad, 'locale' => app()->getLocale()]) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Position -->
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700">{{ __('Position') }} <span class="text-red-500">*</span></label>
                            <select id="position" name="position" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">{{ __('Sélectionnez une position') }}</option>
                                @foreach($positions as $value => $label)
                                    <option value="{{ $value }}" {{ old('position', $ad->position) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('position')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">{{ __('Type') }} <span class="text-red-500">*</span></label>
                            <select id="type" name="type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" x-data x-model="$store.adForm.type">
                                <option value="image" {{ old('type', $ad->type) == 'image' ? 'selected' : '' }}>{{ __('Image') }}</option>
                                <option value="adsense" {{ old('type', $ad->type) == 'adsense' ? 'selected' : '' }}>{{ __('Code AdSense') }}</option>
                            </select>
                            @error('type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image Fields -->
                        <div x-data x-show="$store.adForm.type === 'image'">
                            <!-- Image Path -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">{{ __('Chemin de l\'image') }}</label>
                                <input type="text" name="image" id="image" value="{{ old('image', $ad->image) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">{{ __('Exemple: images/ads/banner.jpg') }}</p>
                                @error('image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Link -->
                            <div class="mt-4">
                                <label for="link" class="block text-sm font-medium text-gray-700">{{ __('Lien URL') }}</label>
                                <input type="url" name="link" id="link" value="{{ old('link', $ad->link) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('link')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alt Text -->
                            <div class="mt-4">
                                <label for="alt" class="block text-sm font-medium text-gray-700">{{ __('Texte alternatif') }}</label>
                                <input type="text" name="alt" id="alt" value="{{ old('alt', $ad->alt) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('alt')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- AdSense Fields -->
                        <div x-data x-show="$store.adForm.type === 'adsense'">
                            <!-- AdSense Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">{{ __('Code AdSense') }}</label>
                                <textarea name="code" id="code" rows="6" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('code', $ad->code) }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">{{ __('Collez ici le code JavaScript fourni par Google AdSense') }}</p>
                                @error('code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Display Options -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Afficher sur') }}</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($displayOptions as $value => $label)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="display_{{ $value }}" name="display_on[]" value="{{ $value }}" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" {{ in_array($value, old('display_on', json_decode($ad->display_on) ?? [])) ? 'checked' : '' }}>
                                        <label for="display_{{ $value }}" class="ml-2 block text-sm text-gray-700">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('display_on')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">{{ __('Priorité') }}</label>
                            <input type="number" name="priority" id="priority" value="{{ old('priority', $ad->priority) }}" min="0" class="mt-1 block w-40 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">{{ __('Plus la valeur est élevée, plus la priorité est haute') }}</p>
                            @error('priority')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" id="active" name="active" value="1" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" {{ old('active', $ad->active) ? 'checked' : '' }}>
                            <label for="active" class="ml-2 block text-sm text-gray-700">{{ __('Activer cette publicité') }}</label>
                        </div>

                        <div class="pt-5">
                            <div class="flex justify-end">
                                <a href="{{ route('admin.ads.index', ['locale' => app()->getLocale()]) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Annuler') }}
                                </a>
                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                    {{ __('Mettre à jour') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('adForm', {
                type: '{{ old('type', $ad->type) }}'
            })
        })
    </script>
    @endpush
</x-app-layout> 
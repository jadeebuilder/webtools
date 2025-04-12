<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Modifier une publicité') }}
                </h1>
                <a href="{{ route('admin.ads.index', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour') }}
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.ads.update', ['locale' => app()->getLocale(), 'ad_id' => $ad->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ 
                        type: '{{ old('type', $ad->type) }}',
                        imageType: '{{ old('image_type', $imageType) }}'
                    }">
                        @csrf
                        @method('PUT')

                        <!-- Position -->
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Position') }} <span class="text-red-500">*</span></label>
                            <select id="position" name="position" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:text-gray-300 sm:text-sm">
                                <option value="">{{ __('Sélectionnez une position') }}</option>
                                @foreach($positions as $value => $label)
                                    <option value="{{ $value }}" {{ old('position', $ad->position) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('position')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Type') }} <span class="text-red-500">*</span></label>
                            <select id="type" name="type" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:text-gray-300 sm:text-sm" x-model="type">
                                <option value="image" {{ old('type', $ad->type) == 'image' ? 'selected' : '' }}>{{ __('Image') }}</option>
                                <option value="adsense" {{ old('type', $ad->type) == 'adsense' ? 'selected' : '' }}>{{ __('Code AdSense') }}</option>
                            </select>
                            @error('type')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image Fields -->
                        <div x-show="type === 'image'">
                            <!-- Source de l'image -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Source de l\'image') }}</label>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="image_type" value="upload" x-model="imageType" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-900" {{ old('image_type') == 'upload' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Téléverser une image') }}</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="image_type" value="external" x-model="imageType" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-900" {{ old('image_type') == 'external' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('URL externe') }}</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="image_type" value="path" x-model="imageType" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-900" {{ old('image_type', 'path') == 'path' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Chemin relatif') }}</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Image courante -->
                            @if($ad->image && file_exists(public_path($ad->image)))
                                <div class="mb-4 p-4 border border-gray-200 dark:border-gray-700 rounded-md">
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Image actuelle') }}</h3>
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ asset($ad->image) }}" alt="{{ $ad->alt }}" class="h-20 w-auto object-contain rounded border border-gray-200 dark:border-gray-700">
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ad->image }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ __('La nouvelle image remplacera celle-ci si une est fournie') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Image Upload -->
                            <div x-show="imageType === 'upload'">
                                <label for="image_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Téléverser une image') }}</label>
                                <input type="file" name="image_file" id="image_file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-700 dark:file:bg-purple-900 dark:file:text-purple-200 hover:file:bg-purple-100 dark:hover:file:bg-purple-800 border border-gray-300 dark:border-gray-600 rounded-md">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Formats acceptés: JPG, PNG, GIF. Taille max: 2MB') }}</p>
                                @error('image_file')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- External URL -->
                            <div x-show="imageType === 'external'">
                                <label for="image_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('URL de l\'image') }}</label>
                                <input type="url" name="image_url" id="image_url" value="{{ old('image_url', Str::startsWith($ad->image, 'http') ? $ad->image : '') }}" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-300 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Exemple: https://example.com/images/banner.jpg') }}</p>
                                @error('image_url')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Relative Path -->
                            <div x-show="imageType === 'path'">
                                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Chemin relatif de l\'image') }}</label>
                                <input type="text" name="image" id="image" value="{{ old('image', $ad->image) }}" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-300 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Exemple: images/ads/banner.jpg') }}</p>
                                @error('image')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Link -->
                            <div class="mt-4">
                                <label for="link" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Lien URL') }}</label>
                                <input type="url" name="link" id="link" value="{{ old('link', $ad->link) }}" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-300 sm:text-sm">
                                @error('link')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alt Text -->
                            <div class="mt-4">
                                <label for="alt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Texte alternatif') }}</label>
                                <input type="text" name="alt" id="alt" value="{{ old('alt', $ad->alt) }}" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-300 sm:text-sm">
                                @error('alt')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- AdSense Fields -->
                        <div x-show="type === 'adsense'">
                            <!-- AdSense Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Code AdSense') }}</label>
                                <textarea name="code" id="code" rows="6" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-300 sm:text-sm">{{ old('code', $ad->code) }}</textarea>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Collez ici le code JavaScript fourni par Google AdSense') }}</p>
                                @error('code')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Display Options -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Afficher sur') }}</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($displayOptions as $value => $label)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="display_{{ $value }}" name="display_on[]" value="{{ $value }}" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-900" {{ in_array($value, old('display_on', json_decode($ad->display_on) ?? [])) ? 'checked' : '' }}>
                                        <label for="display_{{ $value }}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('display_on')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Priorité') }}</label>
                            <input type="number" name="priority" id="priority" value="{{ old('priority', $ad->priority) }}" min="0" class="mt-1 block w-40 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-300 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Plus la valeur est élevée, plus la priorité est haute') }}</p>
                            @error('priority')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" id="active" name="active" value="1" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-900" {{ old('active', $ad->active) ? 'checked' : '' }}>
                            <label for="active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">{{ __('Activer cette publicité') }}</label>
                        </div>

                        <div class="pt-5">
                            <div class="flex justify-end">
                                <a href="{{ route('admin.ads.index', ['locale' => app()->getLocale()]) }}" class="bg-white dark:bg-gray-800 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
</x-admin-layout> 
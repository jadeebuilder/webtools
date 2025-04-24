<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier la méthode de paiement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.payments.methods.update', ['locale' => app()->getLocale(), 'method' => $method->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Nom') }}
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $method->name) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Code') }}
                                </label>
                                <input type="text" name="code" id="code" value="{{ old('code', $method->code) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                @error('code')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Description') }}
                                </label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">{{ old('description', $method->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Classe du processeur -->
                            <div>
                                <label for="processor_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Classe du processeur') }}
                                </label>
                                <input type="text" name="processor_class" id="processor_class" value="{{ old('processor_class', $method->processor_class) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                @error('processor_class')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Icône -->
                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Icône') }}
                                </label>
                                <input type="text" name="icon" id="icon" value="{{ old('icon', $method->icon) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                @error('icon')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ordre d'affichage -->
                            <div>
                                <label for="display_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Ordre d\'affichage') }}
                                </label>
                                <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $method->display_order) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                @error('display_order')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Statut actif -->
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Statut') }}
                                </label>
                                <div class="mt-1">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" id="is_active" value="1"
                                            {{ old('is_active', $method->is_active) ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                            {{ $method->is_active ? __('Activé') : __('Désactivé') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Paramètres -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Paramètres') }}
                            </h3>
                            <div class="space-y-4">
                                @if($method->settings)
                                    @foreach($method->settings as $key => $value)
                                        <div>
                                            <label for="settings_{{ $key }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                                            </label>
                                            <input type="text" name="settings[{{ $key }}]" id="settings_{{ $key }}" value="{{ old("settings.$key", $value) }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('admin.payments.methods.index', ['locale' => app()->getLocale()]) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-3">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 focus:bg-purple-500 active:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Mettre à jour') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 
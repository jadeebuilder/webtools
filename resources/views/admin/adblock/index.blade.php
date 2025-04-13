<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Configuration de la détection d\'AdBlock') }}
                </h1>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.adblock.test', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-vial mr-2"></i> {{ __('Tester la détection') }}
                    </a>
                    <a href="{{ route('admin.ads.index', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour') }}
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Paramètres généraux') }}</h2>
                </div>
                <form action="{{ route('admin.adblock.update', ['locale' => app()->getLocale()]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-6">
                        <!-- Activer la détection -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ __('Activer la détection d\'AdBlock') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Activez ou désactivez complètement la détection d\'AdBlock sur votre site.') }}</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="enabled" value="0">
                                <input type="checkbox" name="enabled" value="1" class="sr-only peer" {{ $settings['enabled'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                            </label>
                        </div>

                        <!-- Bloquer le contenu -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ __('Bloquer l\'accès au contenu') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Empêche les utilisateurs avec AdBlock d\'accéder au contenu du site.') }}</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="block_content" value="0">
                                <input type="checkbox" name="block_content" value="1" class="sr-only peer" {{ $settings['block_content'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                            </label>
                        </div>

                        <!-- Afficher un message -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ __('Afficher un message') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Affiche un message d\'avertissement aux utilisateurs avec AdBlock.') }}</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="show_message" value="0">
                                <input type="checkbox" name="show_message" value="1" id="show_message" class="sr-only peer" {{ $settings['show_message'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                            </label>
                        </div>

                        <!-- Configuration du message -->
                        <div id="message_settings" class="space-y-4 border p-4 rounded-lg {{ $settings['show_message'] ? '' : 'hidden' }}">
                            <div>
                                <label for="message_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Titre du message') }}</label>
                                <input type="text" name="message_title" id="message_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ $settings['message_title'] }}">
                            </div>
                            
                            <div>
                                <label for="message_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Contenu du message') }}</label>
                                <textarea name="message_text" id="message_text" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ $settings['message_text'] }}</textarea>
                            </div>
                            
                            <div>
                                <label for="message_button" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Texte du bouton') }}</label>
                                <input type="text" name="message_button" id="message_button" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ $settings['message_button'] }}">
                            </div>
                            
                            <div>
                                <label for="countdown" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Temps d\'attente avant de pouvoir fermer (secondes)') }}</label>
                                <input type="number" name="countdown" id="countdown" min="0" max="60" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ $settings['countdown'] }}">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('0 = pas de délai d\'attente') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-right">
                        <button type="submit" class="px-4 py-2 bg-purple-700 text-white rounded-md hover:bg-purple-800 transition-colors">
                            {{ __('Enregistrer les modifications') }}
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="mt-8 bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Aperçu du message') }}</h2>
                </div>
                <div class="p-6">
                    <button id="preview_btn" class="px-4 py-2 bg-purple-700 text-white rounded-md hover:bg-purple-800 transition-colors">
                        {{ __('Afficher l\'aperçu') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showMessageCheckbox = document.getElementById('show_message');
            const messageSettings = document.getElementById('message_settings');
            const previewBtn = document.getElementById('preview_btn');
            
            // Gérer l'affichage des paramètres du message
            showMessageCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    messageSettings.classList.remove('hidden');
                } else {
                    messageSettings.classList.add('hidden');
                }
            });
            
            // Aperçu du message
            previewBtn.addEventListener('click', function() {
                // Récupérer les paramètres actuels
                const title = document.getElementById('message_title').value;
                const text = document.getElementById('message_text').value;
                const buttonText = document.getElementById('message_button').value;
                const countdown = parseInt(document.getElementById('countdown').value);
                
                // Créer l'overlay
                const overlay = document.createElement('div');
                overlay.id = 'adblock-overlay';
                overlay.style.position = 'fixed';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.width = '100%';
                overlay.style.height = '100%';
                overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
                overlay.style.zIndex = '9999';
                overlay.style.display = 'flex';
                overlay.style.alignItems = 'center';
                overlay.style.justifyContent = 'center';
                
                // Créer le conteneur du message
                const messageBox = document.createElement('div');
                messageBox.style.backgroundColor = '#fff';
                messageBox.style.borderRadius = '8px';
                messageBox.style.padding = '30px';
                messageBox.style.maxWidth = '500px';
                messageBox.style.width = '90%';
                messageBox.style.textAlign = 'center';
                messageBox.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.3)';
                
                // Titre
                const titleEl = document.createElement('h2');
                titleEl.style.color = '#660bab';
                titleEl.style.marginBottom = '15px';
                titleEl.style.fontSize = '24px';
                titleEl.textContent = title;
                
                // Texte
                const textEl = document.createElement('p');
                textEl.style.marginBottom = '25px';
                textEl.style.fontSize = '16px';
                textEl.style.lineHeight = '1.5';
                textEl.style.color = '#333';
                textEl.textContent = text;
                
                // Conteneur du bouton et compteur
                const btnContainer = document.createElement('div');
                btnContainer.style.display = 'flex';
                btnContainer.style.flexDirection = 'column';
                btnContainer.style.alignItems = 'center';
                
                // Compteur
                const counter = document.createElement('div');
                counter.style.marginBottom = '15px';
                counter.style.fontSize = '14px';
                counter.style.color = '#666';
                
                // Bouton
                const button = document.createElement('button');
                button.style.backgroundColor = '#660bab';
                button.style.color = '#fff';
                button.style.border = 'none';
                button.style.borderRadius = '4px';
                button.style.padding = '10px 20px';
                button.style.fontSize = '16px';
                button.style.cursor = 'pointer';
                button.style.transition = 'background-color 0.3s';
                button.textContent = buttonText;
                button.disabled = true;
                button.style.opacity = '0.7';
                
                button.addEventListener('mouseover', () => {
                    if (!button.disabled) {
                        button.style.backgroundColor = '#4e0883';
                    }
                });
                
                button.addEventListener('mouseout', () => {
                    if (!button.disabled) {
                        button.style.backgroundColor = '#660bab';
                    }
                });
                
                button.addEventListener('click', () => {
                    document.body.removeChild(overlay);
                });
                
                // Ajouter le compteur si nécessaire
                if (countdown > 0) {
                    let count = countdown;
                    counter.textContent = `Veuillez patienter ${count} secondes...`;
                    
                    const interval = setInterval(() => {
                        count--;
                        counter.textContent = `Veuillez patienter ${count} secondes...`;
                        
                        if (count <= 0) {
                            clearInterval(interval);
                            button.disabled = false;
                            button.style.opacity = '1';
                            counter.textContent = 'Vous pouvez maintenant continuer';
                        }
                    }, 1000);
                    
                    btnContainer.appendChild(counter);
                } else {
                    button.disabled = false;
                    button.style.opacity = '1';
                }
                
                // Assembler l'interface
                btnContainer.appendChild(button);
                messageBox.appendChild(titleEl);
                messageBox.appendChild(textEl);
                messageBox.appendChild(btnContainer);
                overlay.appendChild(messageBox);
                document.body.appendChild(overlay);
            });
        });
    </script>
</x-admin-layout> 
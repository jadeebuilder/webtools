<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Test de la détection d\'AdBlock') }}
                </h1>
                <a href="{{ route('admin.adblock.index', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour aux paramètres') }}
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Instructions') }}</h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        {{ __('Cette page vous permet de tester le fonctionnement de la détection d\'AdBlock. Voici comment procéder :') }}
                    </p>
                    <ol class="list-decimal list-inside space-y-2 text-gray-700 dark:text-gray-300 mb-6">
                        <li>{{ __('Activez ou désactivez votre extension AdBlock pour voir son effet') }}</li>
                        <li>{{ __('Cliquez sur "Déclencher la détection" pour simuler la détection d\'AdBlock') }}</li>
                        <li>{{ __('Observez comment le message s\'affiche en fonction des paramètres définis') }}</li>
                    </ol>
                    
                    <div class="mt-6 flex flex-col sm:flex-row gap-4">
                        <button id="trigger_btn" class="px-4 py-2 bg-purple-700 text-white rounded-md hover:bg-purple-800 transition-colors">
                            <i class="fas fa-play mr-2"></i> {{ __('Déclencher la détection') }}
                        </button>
                        <button id="reset_btn" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i> {{ __('Réinitialiser') }}
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Zone de test') }}</h2>
                </div>
                <div class="p-6">
                    <div id="test_area" class="min-h-[300px] border border-dashed border-gray-300 dark:border-gray-600 rounded-lg flex flex-col items-center justify-center p-6">
                        <div class="ad-container pub_300x250 pub_300x250m pub_728x90 text-ad textAd text_ad text_ads text-ads banner-ad">
                            <p class="bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200 p-3 rounded-lg">
                                {{ __('Ce texte simule une publicité.') }} <br>
                                {{ __('Si vous utilisez un AdBlock, il sera probablement caché.') }}
                            </p>
                        </div>
                        
                        <div class="mt-6 text-center">
                            <div id="result" class="font-semibold text-lg">
                                {{ __('Le test n\'a pas encore été effectué. Cliquez sur "Déclencher la détection".') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const triggerBtn = document.getElementById('trigger_btn');
            const resetBtn = document.getElementById('reset_btn');
            const result = document.getElementById('result');
            const testArea = document.getElementById('test_area');
            
            // Paramètres de détection
            const settings = {
                enabled: {{ $settings['enabled'] ? 'true' : 'false' }},
                block_content: {{ $settings['block_content'] ? 'true' : 'false' }},
                show_message: {{ $settings['show_message'] ? 'true' : 'false' }},
                message_title: "{{ $settings['message_title'] }}",
                message_text: "{{ $settings['message_text'] }}",
                message_button: "{{ $settings['message_button'] }}",
                countdown: {{ $settings['countdown'] }}
            };
            
            // Fonction pour détecter AdBlock
            function detectAdBlock() {
                // Vérifier si l'élément de test est caché par un AdBlock
                const adElement = document.querySelector('.ad-container');
                
                if (!adElement || 
                    window.getComputedStyle(adElement).display === 'none' || 
                    window.getComputedStyle(adElement).visibility === 'hidden' || 
                    adElement.offsetHeight === 0) {
                    
                    result.innerHTML = '<span class="text-red-500">&#10006;</span> ' + 
                        '{{ __("AdBlock détecté! L\'élément de test est caché.") }}';
                    result.className = 'font-semibold text-lg text-red-500 dark:text-red-400';
                    
                    handleDetection(true);
                } else {
                    result.innerHTML = '<span class="text-green-500">&#10004;</span> ' + 
                        '{{ __("Aucun AdBlock détecté. L\'élément de test est visible.") }}';
                    result.className = 'font-semibold text-lg text-green-500 dark:text-green-400';
                    
                    handleDetection(false);
                }
            }
            
            // Gérer la détection
            function handleDetection(detected) {
                if (!detected || !settings.enabled) return;
                
                // Bloquer le contenu si nécessaire
                if (settings.block_content) {
                    testArea.style.filter = 'blur(5px)';
                    testArea.style.pointerEvents = 'none';
                    testArea.style.userSelect = 'none';
                }
                
                // Afficher le message si nécessaire
                if (settings.show_message) {
                    showMessage();
                }
            }
            
            // Afficher le message
            function showMessage() {
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
                const title = document.createElement('h2');
                title.style.color = '#660bab';
                title.style.marginBottom = '15px';
                title.style.fontSize = '24px';
                title.textContent = settings.message_title;
                
                // Texte
                const text = document.createElement('p');
                text.style.marginBottom = '25px';
                text.style.fontSize = '16px';
                text.style.lineHeight = '1.5';
                text.style.color = '#333';
                text.textContent = settings.message_text;
                
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
                button.textContent = settings.message_button;
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
                    reset();
                });
                
                // Ajouter le compteur si nécessaire
                if (settings.countdown > 0) {
                    let count = settings.countdown;
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
                messageBox.appendChild(title);
                messageBox.appendChild(text);
                messageBox.appendChild(btnContainer);
                overlay.appendChild(messageBox);
                document.body.appendChild(overlay);
            }
            
            // Réinitialiser la page
            function reset() {
                // Supprimer l'overlay s'il existe
                const overlay = document.getElementById('adblock-overlay');
                if (overlay) {
                    document.body.removeChild(overlay);
                }
                
                // Réinitialiser le contenu
                testArea.style.filter = '';
                testArea.style.pointerEvents = '';
                testArea.style.userSelect = '';
                
                // Réinitialiser le résultat
                result.innerHTML = '{{ __("Le test n\'a pas encore été effectué. Cliquez sur \"Déclencher la détection\".") }}';
                result.className = 'font-semibold text-lg';
            }
            
            // Événements des boutons
            triggerBtn.addEventListener('click', detectAdBlock);
            resetBtn.addEventListener('click', reset);
        });
    </script>
</x-admin-layout> 
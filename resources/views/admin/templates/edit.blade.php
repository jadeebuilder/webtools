<x-app-layout :pageTitle="__('Configurer le template de :tool', ['tool' => $tool->name])" :metaDescription="__('Configurer les sections de l\'outil :tool', ['tool' => $tool->name])">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Configurer le template de :tool', ['tool' => $tool->name]) }}
            </h1>
            <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div x-data="templateManager({{ json_encode($availableSections) }}, {{ json_encode($toolSections) }})" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Informations sur l'outil -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16 flex items-center justify-center rounded-md bg-purple-100 text-purple-600">
                            <i class="fas fa-{{ $tool->icon ?? 'tools' }} text-2xl"></i>
                        </div>
                        <div class="ml-5">
                            <h2 class="text-xl font-bold text-gray-900">{{ $tool->name }}</h2>
                            <p class="text-sm text-gray-500">{{ __('Catégorie') }}: {{ $tool->category->name }}</p>
                            <div class="mt-1 flex items-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $tool->isPublished() ? __('Publié') : __('Non publié') }}
                                </span>
                                <a href="{{ route('tools.show', ['slug' => $tool->slug, 'locale' => app()->getLocale()]) }}" class="ml-2 text-sm text-indigo-600 hover:text-indigo-800" target="_blank">
                                    <i class="fas fa-external-link-alt mr-1"></i> {{ __('Voir l\'outil') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-5 gap-6">
                <!-- Sections disponibles -->
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 bg-purple-50 border-b border-gray-200">
                            <h2 class="font-medium text-lg text-purple-800">{{ __('Sections disponibles') }}</h2>
                            <p class="text-sm text-purple-600">{{ __('Glissez les sections vers le template de l\'outil') }}</p>
                        </div>
                        <div class="p-4">
                            <div class="available-sections bg-white rounded-lg border-2 border-dashed border-gray-300 p-4 min-h-screen-1/3">
                                <template x-if="availableSections.length === 0">
                                    <div class="text-center py-6 text-gray-500">
                                        <i class="fas fa-puzzle-piece text-4xl mb-2"></i>
                                        <p>{{ __('Toutes les sections ont été ajoutées au template') }}</p>
                                    </div>
                                </template>
                                <template x-for="section in availableSections" :key="section.id">
                                    <div class="section-item bg-white border border-gray-200 rounded-lg mb-3 p-4 shadow-sm cursor-move hover:shadow-md transition duration-200" 
                                        draggable="true"
                                        @dragstart="dragStart($event, section, 'available')"
                                        @dragend="dragEnd">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-md bg-purple-100 text-purple-600">
                                                <i class="fas" :class="'fa-' + section.icon"></i>
                                            </div>
                                            <div class="ml-4 flex-grow">
                                                <h3 class="text-sm font-medium text-gray-900" x-text="section.name"></h3>
                                                <p class="text-xs text-gray-500" x-text="section.description"></p>
                                            </div>
                                            <button type="button" 
                                                @click="addSection(section)"
                                                class="p-2 text-purple-600 hover:bg-purple-100 rounded-full">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Template de l'outil -->
                <div class="md:col-span-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 bg-indigo-50 border-b border-gray-200">
                            <h2 class="font-medium text-lg text-indigo-800">{{ __('Template de l\'outil') }}</h2>
                            <p class="text-sm text-indigo-600">{{ __('Organisez les sections par glisser-déposer') }}</p>
                        </div>
                        <div class="p-4">
                            <div class="template-sections bg-white rounded-lg border-2 border-dashed border-gray-300 p-4 min-h-screen-1/2"
                                @dragover.prevent
                                @drop="drop($event, 'template')">
                                <template x-if="toolSections.length === 0">
                                    <div class="text-center py-10 text-gray-500">
                                        <i class="fas fa-long-arrow-alt-left text-4xl mb-2"></i>
                                        <p>{{ __('Glissez des sections depuis la liste disponible') }}</p>
                                        <p class="text-sm mt-2">{{ __('Ou cliquez sur le + pour ajouter une section') }}</p>
                                    </div>
                                </template>
                                <template x-for="(sectionItem, index) in toolSections" :key="sectionItem.id">
                                    <div class="section-item bg-white border border-gray-200 rounded-lg mb-3 shadow-sm relative hover:shadow transition duration-200" 
                                        :class="{'border-indigo-500': sectionItem.is_active, 'border-red-300 opacity-60': !sectionItem.is_active}" 
                                        draggable="true"
                                        @dragstart="dragStart($event, sectionItem, 'template', index)"
                                        @dragover.prevent
                                        @drop="dropOnItem($event, index)">
                                        <div class="absolute top-2 right-2 flex space-x-1">
                                            <button type="button" 
                                                @click="toggleSectionActive(index)"
                                                class="p-1 rounded-full" 
                                                :class="sectionItem.is_active ? 'text-green-600 hover:bg-green-100' : 'text-red-600 hover:bg-red-100'">
                                                <i class="fas" :class="sectionItem.is_active ? 'fa-eye' : 'fa-eye-slash'"></i>
                                            </button>
                                            <button type="button" 
                                                @click="removeSection(index)"
                                                class="p-1 text-red-600 hover:bg-red-100 rounded-full">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="p-4 pr-16">
                                            <div class="flex items-center">
                                                <div class="mr-3 text-gray-400 cursor-move">
                                                    <i class="fas fa-grip-vertical"></i>
                                                </div>
                                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-md" 
                                                    :class="sectionItem.is_active ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-500'">
                                                    <i class="fas" :class="'fa-' + sectionItem.section.icon"></i>
                                                </div>
                                                <div class="ml-4 flex-grow">
                                                    <h3 class="text-sm font-medium" :class="sectionItem.is_active ? 'text-gray-900' : 'text-gray-500'" x-text="sectionItem.section.name"></h3>
                                                    <p class="text-xs text-gray-500" x-text="sectionItem.section.description"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="button" 
                                    @click="saveTemplate"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    :class="{'opacity-50 cursor-not-allowed': saving}"
                                    :disabled="saving">
                                    <template x-if="saving">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>
                                    </template>
                                    <template x-if="!saving">
                                        <i class="fas fa-save mr-2"></i>
                                    </template>
                                    <span x-text="saving ? '{{ __('Enregistrement...') }}' : '{{ __('Enregistrer le template') }}'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function templateManager(availableSectionsInit, toolSectionsInit) {
            return {
                availableSections: availableSectionsInit,
                toolSections: toolSectionsInit,
                saving: false,
                dragItem: null,
                dragSource: null,
                dragIndex: null,

                dragStart(event, item, source, index = null) {
                    this.dragItem = item;
                    this.dragSource = source;
                    this.dragIndex = index;
                    event.dataTransfer.effectAllowed = 'move';
                },

                dragEnd() {
                    this.dragItem = null;
                    this.dragSource = null;
                    this.dragIndex = null;
                },

                drop(event, target) {
                    if (!this.dragItem) return;
                    
                    if (this.dragSource === 'available' && target === 'template') {
                        this.addSection(this.dragItem);
                    }
                },

                dropOnItem(event, targetIndex) {
                    if (!this.dragItem || !this.dragSource) return;
                    
                    if (this.dragSource === 'template' && this.dragIndex !== null) {
                        // Réorganiser dans le template
                        const item = this.toolSections.splice(this.dragIndex, 1)[0];
                        this.toolSections.splice(targetIndex, 0, item);
                    } else if (this.dragSource === 'available') {
                        // Ajouter depuis les disponibles à un endroit spécifique
                        const section = this.dragItem;
                        const newItem = this.createTemplateItem(section);
                        this.toolSections.splice(targetIndex, 0, newItem);
                        this.removeFromAvailable(section);
                    }
                },

                addSection(section) {
                    const newItem = this.createTemplateItem(section);
                    this.toolSections.push(newItem);
                    this.removeFromAvailable(section);
                },

                createTemplateItem(section) {
                    return {
                        id: Math.random().toString(36).substring(2, 11),
                        section_id: section.id,
                        is_active: true,
                        section: section,
                        settings: {}
                    };
                },

                removeFromAvailable(section) {
                    const index = this.availableSections.findIndex(s => s.id === section.id);
                    if (index !== -1) {
                        this.availableSections.splice(index, 1);
                    }
                },

                removeSection(index) {
                    const section = this.toolSections[index].section;
                    this.toolSections.splice(index, 1);
                    
                    // Remettre la section dans les disponibles
                    if (!this.availableSections.find(s => s.id === section.id)) {
                        this.availableSections.push(section);
                    }
                    
                    // Trier par ordre
                    this.availableSections.sort((a, b) => a.order - b.order);
                },

                toggleSectionActive(index) {
                    this.toolSections[index].is_active = !this.toolSections[index].is_active;
                },

                async saveTemplate() {
                    this.saving = true;
                    
                    try {
                        const response = await fetch('{{ route('admin.templates.update', $tool) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                sections: this.toolSections.map((item, index) => ({
                                    section_id: item.section_id,
                                    is_active: item.is_active,
                                    order: index,
                                    settings: item.settings || {}
                                }))
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            // Afficher un message de succès
                            const alert = document.createElement('div');
                            alert.className = 'fixed top-4 right-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg z-50';
                            alert.innerHTML = `<p><i class="fas fa-check-circle mr-2"></i> ${data.message}</p>`;
                            document.body.appendChild(alert);
                            
                            // Supprimer l'alerte après 3 secondes
                            setTimeout(() => {
                                alert.remove();
                            }, 3000);
                        } else {
                            alert(data.message || 'Une erreur s\'est produite');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Une erreur s\'est produite lors de l\'enregistrement du template');
                    } finally {
                        this.saving = false;
                    }
                }
            };
        }
    </script>
    <style>
        .min-h-screen-1/3 {
            min-height: 33vh;
        }
        .min-h-screen-1/2 {
            min-height: 50vh;
        }
    </style>
    @endpush
</x-app-layout> 
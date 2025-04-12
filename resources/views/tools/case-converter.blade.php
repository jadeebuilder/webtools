<x-tool-layout :pageTitle="$pageTitle" :metaDescription="$metaDescription" :customH1="$customH1" :customDescription="$customDescription">
    <div id="case-converter-tool" x-data="{
        text: '',
        conversionType: 'lowercase',
        result: null,
        processing: false,
        copied: false,
        
        async convert() {
            if (!this.text) {
                alert('{{ __('tools.case_converter.text_required') }}');
                return;
            }
            
            this.processing = true;
            this.result = null;
            
            try {
                const response = await fetch('{{ route('tools.case-converter.process', ['locale' => app()->getLocale()]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        text: this.text,
                        conversion_type: this.conversionType
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.result = data.data;
                } else {
                    alert(data.message || '{{ __('tools.error') }}');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('{{ __('tools.error') }}');
            } finally {
                this.processing = false;
            }
        },
        
        copyToClipboard() {
            if (!this.result) return;
            
            navigator.clipboard.writeText(this.result.converted).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            });
        },
        
        clearAll() {
            this.text = '';
            this.result = null;
            this.processing = false;
            this.copied = false;
        }
    }">
        <div class="mb-6">
            <label for="text" class="block text-sm font-medium text-gray-700 mb-2">{{ __('tools.case_converter.text') }}</label>
            <textarea
                id="text"
                x-model="text"
                rows="6"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="{{ __('tools.case_converter.text') }}"
            ></textarea>
        </div>
        
        <div class="mb-6">
            <label for="conversion-type" class="block text-sm font-medium text-gray-700 mb-2">{{ __('tools.case_converter.conversion_type') }}</label>
            <select
                id="conversion-type"
                x-model="conversionType"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            >
                @foreach($conversionTypes as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="flex flex-wrap gap-3 mb-6">
            <button
                x-on:click="convert"
                x-bind:disabled="processing"
                class="bg-purple-700 hover:bg-purple-800 text-white font-medium py-2 px-4 rounded-md transition-colors duration-300 flex items-center"
                :class="{ 'opacity-70 cursor-not-allowed': processing }"
            >
                <template x-if="processing">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <span x-text="processing ? '{{ __('tools.processing') }}' : '{{ __('tools.case_converter.convert') }}'"></span>
            </button>
            
            <button
                x-on:click="clearAll"
                class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition-colors duration-300"
            >
                {{ __('tools.clear') }}
            </button>
        </div>
        
        <template x-if="result">
            <div class="border rounded-md p-4 bg-gray-50">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('tools.case_converter.converted_text') }}</h3>
                    <div class="flex space-x-2">
                        <button
                            x-on:click="copyToClipboard"
                            class="bg-purple-700 hover:bg-purple-800 text-white font-medium py-1 px-3 rounded-md transition-colors duration-300 text-sm flex items-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            <span x-text="copied ? '{{ __('tools.copied') }}' : '{{ __('tools.copy') }}'"></span>
                        </button>
                    </div>
                </div>
                <div class="bg-white border rounded-md p-3 min-h-[100px] whitespace-pre-wrap break-words" x-text="result.converted"></div>
            </div>
        </template>
    </div>

    <!-- Sections supplémentaires pour cet outil spécifique -->
    <x-slot name="additionalSections">
        @include('partials.home.testimonials')
    </x-slot>
</x-tool-layout>
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open"
        class="flex items-center cursor-pointer space-x-1 px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3a48.474 48.474 0 016 0v2.25M9 5.25c0 1.12.038 2.233.114 3.334M9 5.25V3a48.474 48.474 0 016 0v2.25" />
        </svg>
        <span>{{ strtoupper(app()->getLocale()) }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-3 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">

        @php
            $currentSlug = request()->route('slug');
            $translationService = app(\App\Services\TranslationService::class);
            $availableLanguages = $currentSlug
                ? $translationService->getAvailableLanguages(
                    (string) app(\Prezet\Prezet\Models\Document::class)
                        ->where('slug', $currentSlug)
                        ->first()?->id,
                )
                : [];
        @endphp

        {{-- English --}}
        <a href="{{ $currentSlug ? route('prezet.show', ['slug' => $currentSlug]) : request()->url() }}"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ app()->getLocale() === 'en' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
            🇺🇸 {{ __('English') }}
        </a>

        {{-- Vietnamese --}}
        <a href="#" onclick="switchLanguage('vi', '{{ $currentSlug }}', {{ json_encode($availableLanguages) }})"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ app()->getLocale() === 'vi' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
            🇻🇳 {{ __('Vietnamese') }}
        </a>

        {{-- French --}}
        <a href="#" onclick="switchLanguage('fr', '{{ $currentSlug }}', {{ json_encode($availableLanguages) }})"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ app()->getLocale() === 'fr' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
            🇫🇷 {{ __('French') }}
        </a>
    </div>
</div>

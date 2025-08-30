<header class="sticky top-0 z-50 bg-white px-4 py-5 sm:px-6 lg:px-8 dark:bg-zinc-900 shadow-xs shadow-zinc-900/5">
    <div class="grid grid-cols-12">
        <div class="col-span-12 flex flex-none flex-wrap items-center justify-between xl:col-span-10 xl:col-start-2">
            <div class="relative flex grow basis-0 items-center">
                <button aria-label="{{ __('Menu') }}"
                    class="mr-4 rounded-lg p-1.5 hover:bg-gray-100 active:bg-gray-200 lg:hidden"
                    x-on:click="showSidebar = ! showSidebar">
                    <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="4" x2="20" y1="12" y2="12"></line>
                        <line x1="4" x2="20" y1="6" y2="6"></line>
                        <line x1="4" x2="20" y1="18" y2="18"></line>
                    </svg>
                </button>

                <a aria-label="{{ __('Home') }}" href="{{ route('prezet.index') }}"
                    class="block flex items-center space-x-2">
                    <x-prezet.logo />
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ strtoupper(config('app.name')) }}
                    </span>
                </a>
            </div>
            <div class="relative flex basis-0 items-center justify-end gap-3 sm:gap-8 md:grow lg:gap-6">
                <x-prezet.search />

                {{-- Language Switcher --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center cursor-pointer space-x-1 px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3a48.474 48.474 0 016 0v2.25M9 5.25c0 1.12.038 2.233.114 3.334M9 5.25V3a48.474 48.474 0 016 0v2.25" />
                        </svg>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
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
                        <a href="#"
                            onclick="switchLanguage('vi', '{{ $currentSlug }}', {{ json_encode($availableLanguages) }})"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ app()->getLocale() === 'vi' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
                            🇻🇳 {{ __('Vietnamese') }}
                        </a>

                        {{-- French --}}
                        <a href="#"
                            onclick="switchLanguage('fr', '{{ $currentSlug }}', {{ json_encode($availableLanguages) }})"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ app()->getLocale() === 'fr' ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}">
                            🇫🇷 {{ __('French') }}
                        </a>
                    </div>
                </div>

                <a href="/feed" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6 dark:text-gray-400 hover:dark:text-gray-100 text-gray-500 hover:text-primary-800">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12.75 19.5v-.75a7.5 7.5 0 0 0-7.5-7.5H4.5m0-6.75h.75c7.87 0 14.25 6.38 14.25 14.25v.75M6 18.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                </a>
                <a class="group" aria-label="GitHub" href="https://github.com/tqt97" target="_blank">
                    <svg aria-hidden="true" viewBox="0 0 16 16"
                        class="h-6 w-6 fill-gray-500 dark:fill-gray-400 group-hover:dark:fill-gray-100 group-hover:fill-gray-800">
                        <path
                            d="M8 0C3.58 0 0 3.58 0 8C0 11.54 2.29 14.53 5.47 15.59C5.87 15.66 6.02 15.42 6.02 15.21C6.02 15.02 6.01 14.39 6.01 13.72C4 14.09 3.48 13.23 3.32 12.78C3.23 12.55 2.84 11.84 2.5 11.65C2.22 11.5 1.82 11.13 2.49 11.12C3.12 11.11 3.57 11.7 3.72 11.94C4.44 13.15 5.59 12.81 6.05 12.6C6.12 12.08 6.33 11.73 6.56 11.53C4.78 11.33 2.92 10.64 2.92 7.58C2.92 6.71 3.23 5.99 3.74 5.43C3.66 5.23 3.38 4.41 3.82 3.31C3.82 3.31 4.49 3.1 6.02 4.13C6.66 3.95 7.34 3.86 8.02 3.86C8.7 3.86 9.38 3.95 10.02 4.13C11.55 3.09 12.22 3.31 12.22 3.31C12.66 4.41 12.38 5.23 12.3 5.43C12.81 5.99 13.12 6.7 13.12 7.58C13.12 10.65 11.25 11.33 9.47 11.53C9.76 11.78 10.01 12.26 10.01 13.01C10.01 14.08 10 14.94 10 15.21C10 15.42 10.15 15.67 10.55 15.59C13.71 14.53 16 11.53 16 8C16 3.58 12.42 0 8 0Z">
                        </path>
                    </svg>
                </a>

                <button
                    onclick="
                        const isDark = document.documentElement.classList.toggle('dark');
                        localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    "
                    class="group cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="h-6 w-6 block dark:hidden fill-gray-500 text-gray-500 group-hover:fill-gray-900">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75
               c-5.385 0-9.75-4.365-9.75-9.75
               0-1.33.266-2.597.748-3.752
               A9.753 9.753 0 0 0 3 11.25
               C3 16.635 7.365 21 12.75 21
               a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="h-6 w-6 hidden dark:block text-yellow-600 group-hover:text-yellow-300">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1.5M12 19.5V21M4.22 4.22l1.06 1.06M18.72 18.72l1.06 1.06
               M3 12h1.5M19.5 12H21M4.22 19.78l1.06-1.06M18.72 5.28l1.06-1.06
               M12 7.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9z" />
                    </svg>
                </button>


            </div>
        </div>
    </div>
</header>

<script>
    function switchLanguage(lang, currentSlug, availableLanguages) {
        // If we're on a post page and translation exists, go to translated version
        if (currentSlug && availableLanguages.includes(lang)) {
            window.location.href = `/${currentSlug}/translate/${lang}`;
        } else {
            // Otherwise, just change the UI language
            const url = new URL(window.location);
            url.searchParams.set('lang', lang);
            window.location.href = url.toString();
        }
    }
</script>

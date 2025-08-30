@php
    /* @var string $documentKey */
    /* @var string $currentLanguage */
@endphp

@php
    $translationService = app(\App\Services\TranslationService::class);
    $availableLanguages = $translationService->getAvailableLanguages($documentKey);
    $currentLocale = app()->getLocale();
@endphp

<div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
    <span>{{ __('Available in:') }}</span>
    <div class="flex space-x-1">
        {{-- Original English version --}}
        <a href="{{ route('prezet.show', ['slug' => request()->route('slug')]) }}"
            class="px-2 py-1 rounded text-xs font-medium {{ $currentLocale === 'en' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
            {{ __('EN') }}
        </a>

        {{-- Available translations --}}
        @foreach ($availableLanguages as $lang)
            <a href="{{ route('prezet.translate.show', ['slug' => request()->route('slug'), 'lang' => $lang]) }}"
                class="px-2 py-1 rounded text-xs font-medium {{ $lang === $currentLocale ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                {{ strtoupper($lang) }}
            </a>
        @endforeach
    </div>
</div>

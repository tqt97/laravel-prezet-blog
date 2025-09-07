<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" />
    <x-prezet.meta />
    <link rel="alternate" type="application/atom+xml" title="News" href="/feed">

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/lite-youtube-embed@0.3.3/src/lite-yt-embed.min.js"></script>
    <script defer src="https://unpkg.com/@benbjurstrom/alpinejs-zoomable@0.4.0/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.14.1/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    @vite(['resources/css/prezet.css'])
    @stack('jsonld')

    <script>
        ;
        (function () {
            const stored = localStorage.getItem('theme')
            const prefersDark = window.matchMedia(
                '(prefers-color-scheme: dark)'
            ).matches
            const useDark =
                stored === 'dark' || (stored === null && prefersDark)

            if (useDark) {
                document.documentElement.classList.add('dark')
            }
        })()
    </script>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-zinc-900">
    <div class="min-h-screen">
        <x-top />
        <x-prezet.header />
        <div class="relative max-w-7xl mx-auto mb-12">
            {{ $slot }}
        </div>
    </div>
    {{-- <footer
        class="mt-8 border-t border-zinc-600 dark:border-zinc-700 bg-zinc-900 text-center text-base text-zinc-200">
        <div class="flex items-center justify-center py-4">
            © {{ date('Y') }} The Laravel Blog — An enchanted publication. All Rights Reserved.
        </div>
    </footer> --}}
    <x-scroll-to-top />
</body>

</html>

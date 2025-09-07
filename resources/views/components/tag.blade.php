@props([
    'tag',
])
<a href="{{ route('prezet.index', ['tag' => strtolower($tag)]) }}"
    class="inline-flex items-center font-mono rounded-md bg-zinc-50 hover:text-primary-500 px-2 py-1 text-xs text-zinc-800 ring-1 ring-zinc-500/10 transition ring-inset hover:bg-zinc-100 dark:bg-zinc-700 dark:text-zinc-200 dark:ring-zinc-700 dark:hover:bg-zinc-600">
    <x-prezet.icon-tag class="mr-1 h-3 w-3" />

    {{ $tag }}
</a>

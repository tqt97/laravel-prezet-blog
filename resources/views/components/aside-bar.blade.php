@props(['categories', 'categoryCounts'])

<aside class="md:col-span-3 md:block hidden  min-h-screen">
    <div class="sticky top-20">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-zinc-200 dark:border-zinc-800"></div>
            </div>
            <div class="relative flex justify-start">
                <h3 class="bg-white pr-4 text-xl font-bold text-zinc-500 dark:bg-zinc-950 dark:text-zinc-400">
                    {{ __('Categories') }}
                </h3>
            </div>
        </div>
        <div class="relative my-2">
            <ul>
                <li>
                    @foreach ($categories as $category)
                        <a href="{{ route('prezet.show', ['slug' => Str::slug($category)]) }}"
                            class="block text-sm py-1 pr-4 pl-3 text-zinc-900 dark:text-zinc-100 hover:text-primary-500">
                            {{ Str::title(str_replace('-', ' ', $category)) }}
                            ({{ $categoryCounts[$category] ?? 0 }})
                        </a>
                    @endforeach
                </li>
            </ul>
        </div>
    </div>
</aside>

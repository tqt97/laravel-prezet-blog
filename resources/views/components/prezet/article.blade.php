@props([
    'article',
    'author',
])

<article
    class="rounded-lg bg-white shadow hover:shadow-lg p-6 text-zinc-900 ring-1 ring-zinc-500/10 transition-colors ring-inset hover:border-zinc-200 dark:border-zinc-800 dark:bg-zinc-800 dark:text-zinc-100 dark:ring-zinc-700 dark:hover:border-zinc-700"
>
    <div class="space-y-3">


        <h2
            class="text-2xl font-sans leading-tight font-semibold transition-colors duration-200 hover:opacity-75 dark:text-white"
        >
            <a href="{{ route('prezet.show', $article->slug) }}" class="hover:text-primary-500">
                {{ $article->frontmatter->title }}
            </a>
        </h2>

        <div class="text-muted-foreground text-sm">
            @if ($article->category)
            {{-- <span class="font-semibold text-xs">Category:</span> --}}
                <a
                    href="{{ route('prezet.show', ['slug' => strtolower($article->category)]) }}"
                    class="font-mono font-medium text-xs text-zinc-900 transition-all hover:opacity-75 dark:text-zinc-200 bg-zinc-100 px-2 py-1 hover:bg-zinc-50 hover:text-primary-500 rounded-md dark:bg-zinc-700 dark:hover:bg-zinc-600"
                    title="Category: {{ Str::title(str_replace('-', ' ', $article->category))}}"
                    aria-label="Category: {{ Str::title(str_replace('-', ' ', $article->category))}}"
                >
                    {{ Str::title(str_replace('-', ' ', $article->category))}}
                </a>
            @endif
        </div>

        <p class="text-muted-foreground dark:text-zinc-400 line-clamp-2 md:line-clamp-3">
            {{  Str::words($article->frontmatter->excerpt) }}
        </p>

        <div
            class="text-muted-foreground flex items-center gap-2 text-sm dark:text-zinc-400"
        >
            <a
                href="{{ route('prezet.index', ['author' => strtolower($article->frontmatter->author)]) }}"
                class="group flex items-center gap-2"
            >
                <div
                    class="flex h-6 w-6 items-center justify-center overflow-hidden rounded"
                >
                    <img
                        src="{{ $author['image'] ?? '' }}"
                        alt="{{ $post->author->name ?? 'Author' }}"
                        class="h-full w-full rounded bg-zinc-100 object-cover transition-all duration-300 group-hover:opacity-75 dark:bg-zinc-800"
                    />
                </div>
                <span
                    class="group-hover:text-primary transition-all duration-300 dark:group-hover:text-zinc-300"
                >
                    {{ $author['name'] ?? 'Anonymous' }}
                </span>
            </a>

            <span>—</span>

            <div class="flex items-center gap-1">
                <x-prezet.icon-calendar class="size-3.5" />

                <time datetime="{{ $article->createdAt->toIso8601String() }}">
                    {{ $article->createdAt->format('F j, Y') }}
                </time>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 pt-1">
            @foreach ($article->frontmatter->tags as $tag)
                {{-- <a
                    href="{{ route('prezet.index', ['tag' => strtolower($tag)]) }}"
                    class="inline-flex items-center font-mono rounded-md bg-zinc-50 hover:text-primary-500 px-2 py-1 text-xs text-zinc-800 ring-1 ring-zinc-500/10 transition ring-inset hover:bg-zinc-100 dark:bg-zinc-700 dark:text-zinc-200 dark:ring-zinc-700 dark:hover:bg-zinc-600"
                >
                    <x-prezet.icon-tag class="mr-1 h-3 w-3" />

                    {{ $tag }}
                </a> --}}
                <x-tag :tag="$tag" />
            @endforeach
        </div>
    </div>
</article>

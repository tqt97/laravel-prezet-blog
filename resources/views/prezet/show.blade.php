@php
    /* @var string $body */
    /* @var array $nav */
    /* @var array $headings */
    /* @var string $linkedData */
    /* @var \Prezet\Prezet\Data\DocumentData $document */
@endphp

<x-prezet.template>
    @seo([
        'title' => $document->frontmatter->title,
        'description' => $document->frontmatter->excerpt,
        'url' => route('prezet.show', ['slug' => $document->slug]),
        'image' => url($document->frontmatter->image),
    ])

    @push('jsonld')
        <script type="application/ld+json">
                                                                        {!! $linkedData !!}
                                                                    </script>
    @endpush

    <x-prezet.alpine>
        <div class="grid grid-cols-12 gap-4 mt-6 max-w-7xl mx-auto px-6 xl:p-0">
            {{-- Hero Image --}}
            <div class="-mx-8 sm:mx-0 col-span-12 xl:col-start-2 xl:col-span-10 lg:my-4">
                <img src="{{ $document->frontmatter->image ?? '/prezet/img/laravel.jpg' }}"
                    alt="{{ $document->frontmatter->title ?? 'The laravel blog' }}" width="1120" height="595"
                    loading="lazy" decoding="async"
                    class="h-auto max-h-[500px] w-full rounded-lg bg-zinc-50 object-cover dark:bg-zinc-800" />
            </div>
            <div class="col-span-12 xl:col-span-10 xl:col-start-2">
                <h1
                    class="mb-6 text-3xl !leading-snug font-bold sm:text-4xl md:mb-1 lg:text-5xl lg:!leading-tight dark:text-white">
                    {{ $document->title ?? $document->frontmatter->title }}
                </h1>
                {{-- <x-prezet.language-switcher :document-key="(string) $document->id"
                    :current-language="app()->getLocale()" /> --}}
                <div class="flex flex-wrap justify-between items-center gap-3 font-medium text-sm mt-6">
                    {{-- <div>
                        @if ($document->category)
                        <p class="flex items-center dark:text-white">
                            <a href="{{ route('prezet.show', ['slug' => strtolower($document->category)]) }}">
                                {{ Str::title(str_replace('-', ' ', $document->category)) }}
                            </a>
                        </p>
                        @endif
                    </div> --}}
                    {{-- <div class="flex flex-end items-center gap-2"> --}}
                        <p class="w-full sm:w-auto dark:text-white">
                            <a href="#author" class="group flex items-center gap-x-2">
                                <img src="{{ $author['image'] }}" alt="{{ $author['name'] }} profile image" width="20"
                                    height="20" loading="lazy" decoding="async"
                                    class="h-[20px] w-[20px] rounded bg-zinc-100 object-cover transition-all duration-300 group-hover:opacity-75 dark:bg-zinc-800" />
                                <span class="group-hover:text-primary transition-all duration-300">
                                    {{ $author['name'] }}
                                </span>
                            </a>
                        </p>
                        {{-- <p class="hidden text-zinc-600 sm:inline-block dark:text-zinc-400">
                            —
                        </p> --}}
                        <p class="flex items-center gap-1 text-zinc-600 dark:text-zinc-400">
                            <x-prezet.icon-calendar class="size-5 text-primary-500" />
                            <span>{{ $document->createdAt->format('M d, Y') }}</span>
                        </p>
                        {{--
                    </div> --}}

                </div>
            </div>


            {{-- <div class="-mx-8 sm:mx-0 col-span-12 xl:col-start-2 xl:col-span-10 lg:my-4">
                <img src="{{ $document->frontmatter->image ?? '/prezet/img/laravel.jpg' }}" alt="bob" width="1120"
                    height="595" loading="lazy" decoding="async"
                    class="h-auto max-h-[500px] w-full sm:rounded-2xl bg-zinc-50 object-cover dark:bg-zinc-800" />
            </div>

            <div class="col-span-12 xl:col-span-10 xl:col-start-2 2xl:col-span-8 2xl:col-start-4">
                <div class="h-px w-full border-0 bg-zinc-200 dark:bg-zinc-700"></div>
            </div> --}}

            <div class="col-span-12 xl:col-span-10 xl:col-start-2">
                <div class="h-px w-full border-0 bg-zinc-300 dark:bg-zinc-700"></div>
            </div>

            {{-- Right Sidebar --}}
            <div class="col-span-12 lg:order-last lg:col-span-2 mt-6 lg:border-l border-dashed lg:pl-4 border-gray-200">
                <div class="flex-none overflow-y-auto lg:sticky lg:top-[6rem] lg:h-[calc(100vh-4.75rem)]">
                    <nav aria-labelledby="on-this-page-title">
                        <p id="on-this-page-title"
                            class="font-display text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            {{ __('toc') }}
                        </p>
                        <ol role="list" class="mt-4 space-y-3 text-xs">
                            @foreach ($headings as $h2)
                                <li>
                                    <a href="#{{ $h2['id'] }}"
                                        :class="{ '!text-primary-500 !dark:text-primary-400 !hover:text-primary-500': activeHeading === '{{ $h2['id'] }}' }"
                                        x-on:click.prevent="scrollToHeading('{{ $h2['id'] }}')"
                                        class="text-zinc-700 hover:text-primary-500 transition-colors dark:text-zinc-300">
                                        {{ $h2['title'] }}
                                    </a>

                                    @if ($h2['children'])
                                        <ol role="list" class="mt-2 space-y-3 border-l pl-5">
                                            @foreach ($h2['children'] as $h3)
                                                <li>
                                                    <a href="#{{ $h3['id'] }}"
                                                        :class="{ '!text-primary-500 !dark:text-primary-400 !hover:text-primary-500': activeHeading === '{{ $h3['id'] }}' }"
                                                        x-on:click.prevent="scrollToHeading('{{ $h3['id'] }}')"
                                                        class="text-zinc-700 transition-colors dark:text-zinc-300 hover:text-primary-500">
                                                        {{ $h3['title'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ol>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="col-span-12 lg:hidden">
                <div class="h-px w-full border-0 bg-zinc-200 dark:bg-zinc-700"></div>
            </div>

            {{-- Main Content --}}
            <div class="col-span-12 lg:col-span-9 xl:col-span-8 xl:col-start-2 mt-6">
                {{-- prose-pre:-mx-8 prose-pre:rounded-none --}}
                <article
                    class="prose-pre:rounded-xl prose-headings:font-display prose prose-zinc prose-a:border-b prose-a:border-dashed prose-a:border-black/30 prose-a:font-semibold prose-a:no-underline prose-a:hover:border-solid prose-img:rounded-sm dark:prose-invert max-w-none">
                    {!! $body !!}
                </article>

                <div
                    class="border-dark/5 my-10 flex flex-col justify-start gap-y-5 border-t pt-10 border-dashed border-[#1a1a1a]/30">
                    @if ($document->frontmatter->tags)
                        <div class="inline-flex items-center gap-2">

                            <ul class="flex flex-wrap items-center gap-2 sm:gap-3">
                                <li>
                                    @foreach ($document->frontmatter->tags as $tag)
                                        {{-- <a href="{{ route('prezet.index', ['tag' => strtolower($tag)]) }}"
                                            class="inline-flex items-center rounded-md bg-zinc-50 px-3 py-1.5 text-xs text-zinc-800 ring-1 ring-zinc-500/10 transition ring-inset hover:bg-zinc-200 dark:bg-zinc-700 dark:text-zinc-200 dark:ring-zinc-700 dark:hover:bg-zinc-600">
                                            <x-prezet.icon-tag class="mr-1 h-3 w-3" />

                                            {{ $tag }}
                                        </a> --}}
                                        <x-tag :tag="$tag" />

                                    @endforeach
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
                <x-author :author="$author" :authorId="$document->frontmatter->author" />
            </div>
        </div>
    </x-prezet.alpine>
</x-prezet.template>

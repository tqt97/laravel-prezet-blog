@php
    /* @var \Prezet\Prezet\Models\Document $document */
    /* @var string $language */
@endphp

<x-prezet.template>
    @seo([
        'title' => 'Translate: ' . $document->title,
        'description' => 'Translate this article to ' . strtoupper($language),
        'url' => route('prezet.translate.create', ['slug' => $document->slug]),
    ])

    <div class="mx-auto max-w-4xl px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold dark:text-white mb-4">
                {{ __('Translate') }}: {{ $document->title }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('Translate to:') }} <span class="font-semibold">{{ strtoupper($language) }}</span>
            </p>
        </div>

        <form action="{{ route('prezet.translate.store', ['slug' => $document->slug]) }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="language" value="{{ $language }}">

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Title') }} ({{ strtoupper($language) }})
                </label>
                <input type="text" id="title" name="title" value="{{ old('title', $document->title) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    required>
                @if (isset($errors) && $errors->has('title'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('title') }}</p>
                @endif
            </div>

            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Excerpt') }} ({{ strtoupper($language) }})
                </label>
                <textarea id="excerpt" name="excerpt" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">{{ old('excerpt', $document->excerpt) }}</textarea>
                @if (isset($errors) && $errors->has('excerpt'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('excerpt') }}</p>
                @endif
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Content') }} ({{ strtoupper($language) }}) - Markdown
                </label>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <textarea id="content" name="content" rows="20"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white font-mono text-sm"
                            placeholder="{{ __('Write your translated content in Markdown format...') }}" required>{{ old('content', $document->content) }}</textarea>
                        @if (isset($errors) && $errors->has('content'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('content') }}</p>
                        @endif
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-md">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Preview') }}</h3>
                        <div id="preview" class="prose prose-sm max-w-none dark:prose-invert">
                            <!-- Preview will be rendered here -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('prezet.show', ['slug' => $document->slug]) }}"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    {{ __('Save Translation') }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const contentTextarea = document.getElementById('content');
                const previewDiv = document.getElementById('preview');

                function updatePreview() {
                    const markdown = contentTextarea.value;
                    const html = marked.parse(markdown);
                    previewDiv.innerHTML = html;
                }

                contentTextarea.addEventListener('input', updatePreview);
                updatePreview(); // Initial preview
            });
        </script>
    @endpush
</x-prezet.template>

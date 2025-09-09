<x-prezet.template>
    @seo(['title' => 'Create New Post'])

    <div class="max-w-5xl mx-auto px-6 xl:p-0 py-12">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between mt-6">
                <div class="flex-1 min-w-0">
                    <h1 class="text-3xl font-bold leading-tight text-zinc-900 dark:text-white sm:truncate">
                        Create New Post
                    </h1>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="#" class="inline-flex items-center rounded-md border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-200 shadow-sm hover:bg-zinc-50 dark:hover:bg-zinc-700">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex items-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700">
                        Publish Post
                    </button>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative my-6" role="alert">
                    <strong class="font-bold">Whoops! There were some problems with your input.</strong>
                    <ul class="mt-3 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Content -->
            <div class="mt-8 py-2 grid grid-cols-1 gap-y-8 gap-x-8 lg:grid-cols-4">
                <!-- Main Content Column (3/4) -->
                <div class="lg:col-span-3 space-y-4 bg-white">
                    <div class="px-6 py-2">
                        <label for="title" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Title <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required class="mt-1 block w-full border-zinc-300 dark:border-zinc-700 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm bg-white dark:bg-zinc-900">
                    </div>

                    <div class="px-6 py-2">
                        <label for="excerpt" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Excerpt <span class="text-red-500">*</span></label>
                        <textarea id="excerpt" name="excerpt" required rows="4" class="mt-1 block w-full border-zinc-300 dark:border-zinc-700 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm bg-white dark:bg-zinc-900">{{ old('excerpt') }}</textarea>
                    </div>

                    <div class="px-6 py-2">
                        <label for="content" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Content (Markdown) <span class="text-red-500">*</span></label>
                        <textarea id="content" name="content" required rows="12" class="mt-1 block w-full border-zinc-300 dark:border-zinc-700 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm bg-white dark:bg-zinc-900 font-mono text-sm">{{ old('content') }}</textarea>
                    </div>
                </div>

                <!-- Sidebar Column (1/4) -->
                <div class="lg:col-span-1 bg-white">
                    <div class="px-6 py-2" x-data="{ imagePreview: null, fileName: '' }">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Featured Image</label>
                        <div class="mt-2 flex justify-center rounded-md border-2 border-dashed border-zinc-300 dark:border-zinc-600 px-6 pt-5 pb-6">
                            <div class="space-y-1 text-center">
                                <template x-if="!imagePreview">
                                    <svg class="mx-auto h-12 w-12 text-zinc-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                </template>
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="mx-auto max-h-40 rounded-md">
                                </template>
                                <div class=" text-sm text-zinc-600 dark:text-zinc-400">
                                    <label for="image" class="relative cursor-pointer rounded-md bg-white dark:bg-transparent font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                        <span>Upload a file</span>
                                        <input id="image" name="image" type="file" class="sr-only" @change="fileName = $event.target.files[0].name; const reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result }; reader.readAsDataURL($event.target.files[0]);">
                                    </label>
                                    <p>or drag and drop</p>
                                    <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-500" x-text="fileName || 'PNG, JPG, WEBP up to 2MB'"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-2 space-y-4">
                        <div>
                            <label for="author" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Author <span class="text-red-500">*</span></label>
                            <select name="author" id="author" required class="mt-1 block text-sm w-full border-zinc-300 dark:border-zinc-700 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm bg-white dark:bg-zinc-900">
                                <option value="">Select an author</option>
                                @foreach($authors as $key => $author)
                                    <option value="{{ $key }}" {{ old('author') == $key ? 'selected' : '' }}>{{ $author['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Category</label>
                            <select name="category" id="category" class="mt-1 block text-sm w-full border-zinc-300 dark:border-zinc-700 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm bg-white dark:bg-zinc-900">
                                <option value="" selected>Select a category (optional)</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ Str::title(str_replace('-', ' ', $category)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="tags" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Tags (comma-separated)</label>
                            <input type="text" id="tags" name="tags" value="{{ old('tags') }}" class="mt-1 block w-full border-zinc-300 dark:border-zinc-700 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm bg-white dark:bg-zinc-900">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-prezet.template>

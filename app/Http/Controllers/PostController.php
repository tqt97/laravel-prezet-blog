<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Services\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Prezet\Prezet\Models\Document;

class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $authors = config('prezet.authors', []);

        $categories = Document::query()
            ->where('content_type', 'article')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('posts.create', [
            'authors' => $authors,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $slug = Str::slug($validatedData['title']);
            $imageFile = $request->file('image');
            $imageExtension = $imageFile->getClientOriginalExtension();
            $imageName = $slug.'.'.$imageExtension;

            // Store in public/prezet/images, which is accessible via /prezet/images/
            $path = $imageFile->storeAs('images', $imageName, 'prezet');

            // The path stored in front matter should be web-accessible
            $validatedData['image_path'] = '/prezet/'.$path;
        }

        $slug = $this->postService->createPost($validatedData);

        // Clear the cache so the new post appears
        $this->clearCache();

        return redirect()->route('prezet.show', ['slug' => $slug])
            ->with('success', 'Post created successfully!');
    }

    private function clearCache(): void
    {
        // These commands are from the previous performance optimization step
        Artisan::call('optimize:clear');
        // Artisan::call('optimize:cache');
        Artisan::call('prezet:index');
    }
}

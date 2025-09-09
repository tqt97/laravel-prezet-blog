<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PostService
{
    /**
     * Create a new post and save it as a Markdown file.
     *
     * @return string The slug of the new post.
     */
    public function createPost(array $data): string
    {
        $slug = Str::slug($data['title']);
        $date = Carbon::now()->format('Y-m-d');
        $tags = implode(', ', array_map('trim', explode(',', $data['tags'] ?? '')));

        // Use the uploaded image path, or default to a placeholder if none was uploaded
        $image = $data['image_path'] ?? '/prezet/img/laravel.jpg';

        $frontMatter = <<<EOT
---
title: "{$data['title']}"
excerpt: "{$data['excerpt']}"
date: "{$date}"
author: "{$data['author']}"
category: "{$data['category']}"
tags: [{$tags}]
image: "{$image}"
draft: false
---

EOT;

        $content = $frontMatter.$data['content'];
        $fileName = $slug.'.md';
        $filePath = base_path('prezet/content/'.$fileName);

        File::put($filePath, $content);

        return $slug;
    }
}

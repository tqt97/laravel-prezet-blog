<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Prezet\Prezet\Models\Document;

class MakeContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:content
        {title : The title of the content}
        {--path= : Custom path to save file}
        {--category= : Category of the content}
        {--author= : Author of the content}
        {--tags=* : Tags for the content}
        {--draft= : Set draft (true/false)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new content file with front matter';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $title = $this->argument('title');
        $path = $this->option('path')
            ? (Str::startsWith($this->option('path'), 'prezet/')
                ? $this->option('path')
                : 'prezet/content/'.$this->option('path'))
            : 'prezet/content';

        // slug
        $slug = Str::slug($title);
        $filename = $slug.'.md';

        // excerpt required
        $excerpt = $this->ask('Enter excerpt (leave empty to use title)');
        if (empty($excerpt)) {
            $excerpt = $title;
        }

        // create directory if not exists
        if (! is_dir(base_path($path))) {
            mkdir(base_path($path), 0755, true);
        }

        $fullPath = base_path($path.'/'.$filename);

        if (file_exists($fullPath)) {
            $this->error("File already exists: $fullPath");

            return;
        }

        // Draft option
        $draftOption = $this->option('draft');
        $draft = $draftOption !== null ? filter_var($draftOption, FILTER_VALIDATE_BOOLEAN) : true;

        // Category lists
        $categories = app(Document::class)::query()
            ->where('content_type', 'article')
            ->where('draft', false)
            ->get()
            ->pluck('category')
            ->unique()
            ->filter()
            ->toArray();
        $authorsConfig = config('prezet.authors');
        $authors = array_keys($authorsConfig);

        // ask user
        $category = $this->choice('Choose category (or leave default)', $categories, 0);
        $author = $this->choice('Choose author (or leave default)', $authors, 0);
        $tags = $this->ask('Enter tags (comma separated)');

        // normalize values
        $tags = $tags ? array_map('trim', explode(',', $tags)) : [];

        // front matter data
        $frontMatter = [
            'title' => $title,
            'excerpt' => $excerpt,
            'category' => $category,
            'image' => null,
            'draft' => $draft,
            'date' => \Carbon\Carbon::now()->toDateString(),
            'author' => $author,
            'slug' => $slug,
            'key' => (string) Str::uuid(),
            'tags' => $tags,
        ];

        // YAML format
        $yaml = "---\n";
        foreach ($frontMatter as $key => $value) {
            if (is_array($value)) {
                $items = array_map(fn ($v) => "'$v'", $value);
                $yaml .= $key.': ['.implode(', ', $items)."]\n";
            } elseif (is_bool($value)) {
                $yaml .= "$key: ".($value ? 'true' : 'false')."\n";
            } elseif ($value === null) {
                $yaml .= "$key: null\n";
            } else {
                // special case for date
                if ($key === 'date') {
                    $yaml .= "$key: $value\n";
                }
                // nếu có ký tự đặc biệt thì quote
                elseif (preg_match('/[:#\n]/', $value)) {
                    $yaml .= "$key: \"$value\"\n";
                } else {
                    $yaml .= "$key: $value\n";
                }
            }
        }
        $yaml .= "---\n\n";

        // content
        $content = $yaml.'# '.$title."\n\nWrite your content here...";

        file_put_contents($fullPath, $content);

        $this->info("Content created: $fullPath");
        $this->call('prezet:index');
    }
}

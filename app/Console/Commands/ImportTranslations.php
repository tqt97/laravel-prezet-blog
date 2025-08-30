<?php

namespace App\Console\Commands;

use App\Services\TranslationService;
use Illuminate\Console\Command;
use Prezet\Prezet\Models\Document;
use Symfony\Component\Yaml\Yaml;

class ImportTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:import
        {--path=prezet/content : Path to scan for translation files}
        {--language=vi : Language code to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import translations from markdown files';

    /**
     * Execute the console command.
     */
    public function handle(TranslationService $translationService)
    {
        $path = base_path($this->option('path'));
        $language = $this->option('language');

        if (! is_dir($path)) {
            $this->error("Directory not found: {$path}");

            return 1;
        }

        $this->info("Scanning for translation files in: {$path}");
        $this->info("Looking for language: {$language}");

        $files = $this->findTranslationFiles($path, $language);

        if (empty($files)) {
            $this->warn("No translation files found for language: {$language}");

            return 0;
        }

        $this->info('Found '.count($files).' translation files');

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $file) {
            try {
                $this->importTranslationFile($file, $language, $translationService);
                $bar->advance();
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Failed to import {$file}: {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('Import completed!');

        return 0;
    }

    private function findTranslationFiles(string $path, string $language): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'md') {
                $filename = $file->getBasename('.md');
                if (str_ends_with($filename, "-{$language}")) {
                    $files[] = $file->getPathname();
                }
            }
        }

        return $files;
    }

    private function importTranslationFile(string $filepath, string $language, TranslationService $translationService): void
    {
        $content = file_get_contents($filepath);

        // Parse frontmatter
        if (! preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)/s', $content, $matches)) {
            throw new \Exception('Invalid frontmatter format');
        }

        $frontmatter = Yaml::parse($matches[1]);
        $markdownContent = $matches[2];

        // Find original document
        $originalSlug = $frontmatter['slug'] ?? '';
        if (empty($originalSlug)) {
            // Try to find by filename
            $filename = basename($filepath, '.md');
            if (str_ends_with($filename, "-{$language}")) {
                $originalSlug = str_replace("-{$language}", '', $filename);
            } else {
                $originalSlug = $filename;
            }
        } elseif (str_ends_with($originalSlug, "-{$language}")) {
            $originalSlug = str_replace("-{$language}", '', $originalSlug);
        }

        $document = Document::where('slug', $originalSlug)->first();

        if (! $document) {
            throw new \Exception("Original document not found for slug: {$originalSlug}");
        }

        // Create translation
        $translation = $translationService->translateDocument(
            $document,
            $language,
            $frontmatter['title'] ?? $document->title,
            $markdownContent
        );

        // Update with additional frontmatter data
        $translation->excerpt = $frontmatter['excerpt'] ?? $document->excerpt;
        $translation->frontmatter = array_merge($translation->frontmatter, $frontmatter);
        $translation->save();
    }
}

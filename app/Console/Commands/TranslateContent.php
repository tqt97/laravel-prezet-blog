<?php

namespace App\Console\Commands;

use App\Services\TranslationService;
use Illuminate\Console\Command;
use Prezet\Prezet\Models\Document;

class TranslateContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:content
        {--document= : Specific document slug to translate}
        {--language=vi : Target language for translation}
        {--all : Translate all published documents}
        {--dry-run : Show what would be translated without actually doing it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate markdown content to different languages';

    /**
     * Execute the console command.
     */
    public function handle(TranslationService $translationService)
    {
        $language = $this->option('language');
        $dryRun = $this->option('dry-run');

        if ($this->option('document')) {
            $this->translateSingleDocument($this->option('document'), $language, $translationService, $dryRun);
        } elseif ($this->option('all')) {
            $this->translateAllDocuments($language, $translationService, $dryRun);
        } else {
            $this->error('Please specify either --document=slug or --all option');

            return 1;
        }

        return 0;
    }

    private function translateSingleDocument(string $slug, string $language, TranslationService $translationService, bool $dryRun): void
    {
        $document = Document::where('slug', $slug)->first();

        if (! $document) {
            $this->error("Document with slug '{$slug}' not found");

            return;
        }

        if ($dryRun) {
            $this->info("Would translate: {$document->title} to {$language}");

            return;
        }

        $this->info("Translating: {$document->title} to {$language}");

        try {
            $translation = $translationService->translateDocument($document, $language);
            $this->info("✓ Translation created: {$translation->title}");
        } catch (\Exception $e) {
            $this->error("✗ Failed to translate: {$e->getMessage()}");
        }
    }

    private function translateAllDocuments(string $language, TranslationService $translationService, bool $dryRun): void
    {
        $documents = Document::where('content_type', 'article')
            ->where('draft', false)
            ->get();

        if ($dryRun) {
            $this->info("Would translate {$documents->count()} documents to {$language}:");
            foreach ($documents as $document) {
                $this->line("  - {$document->title}");
            }

            return;
        }

        $this->info("Translating {$documents->count()} documents to {$language}...");

        $bar = $this->output->createProgressBar($documents->count());
        $bar->start();

        foreach ($documents as $document) {
            try {
                $translationService->translateDocument($document, $language);
                $bar->advance();
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Failed to translate '{$document->title}': {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('Translation completed!');
    }
}

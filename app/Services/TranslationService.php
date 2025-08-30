<?php

namespace App\Services;

use App\Models\Translation;
use Illuminate\Support\Str;
use Prezet\Prezet\Data\DocumentData;
use Prezet\Prezet\Models\Document;

class TranslationService
{
    public function translateDocument(Document $document, string $targetLanguage, ?string $translatedTitle = null, ?string $translatedContent = null): Translation
    {
        // Get or create translation
        $translation = Translation::firstOrNew([
            'document_key' => (string) $document->id,
            'language' => $targetLanguage,
        ]);

        // Generate translated slug from title
        $slug = $translatedTitle ? Str::slug($translatedTitle) : $document->slug.'-'.$targetLanguage;

        // Update translation data
        $translation->fill([
            'title' => $translatedTitle ?? $document->title,
            'excerpt' => $this->translateExcerpt($document->excerpt, $targetLanguage),
            'content' => $translatedContent ?? $document->content,
            'slug' => $slug,
            'frontmatter' => $this->translateFrontmatter($document->frontmatter, $targetLanguage),
            'is_published' => true,
        ]);

        $translation->save();

        return $translation;
    }

    public function getTranslation(string $documentKey, string $language): ?Translation
    {
        return Translation::where('document_key', $documentKey)
            ->where('language', $language)
            ->where('is_published', true)
            ->first();
    }

    public function getTranslatedDocumentData(Document $document, string $language): ?DocumentData
    {
        $translation = $this->getTranslation((string) $document->id, $language);

        if (! $translation) {
            return null;
        }

        // Create a modified document data with translated content
        $documentData = app(DocumentData::class)::fromModel($document);

        // Override with translated data
        $documentData->title = $translation->title;
        $documentData->excerpt = $translation->excerpt;
        $documentData->slug = $translation->slug;
        // Don't override frontmatter as it expects FrontmatterData object, not array

        return $documentData;
    }

    private function translateExcerpt(?string $excerpt, string $targetLanguage): ?string
    {
        if (! $excerpt) {
            return null;
        }

        // For now, return the original excerpt
        // You can integrate with translation APIs here
        return $excerpt;
    }

    private function translateFrontmatter($frontmatter, string $targetLanguage): array
    {
        // Convert FrontmatterData to array
        $translated = (array) $frontmatter;

        // Add language information
        $translated['language'] = $targetLanguage;
        $translated['original_language'] = 'en';

        return $translated;
    }

    public function getAvailableLanguages(string $documentKey): array
    {
        return Translation::where('document_key', $documentKey)
            ->where('is_published', true)
            ->pluck('language')
            ->toArray();
    }
}

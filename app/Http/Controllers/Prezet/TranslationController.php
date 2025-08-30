<?php

namespace App\Http\Controllers\Prezet;

use App\Services\TranslationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Prezet\Prezet\Models\Document;

class TranslationController
{
    public function __construct(
        private TranslationService $translationService
    ) {}

    public function translate(Request $request, string $slug, string $lang)
    {
        $document = Document::where('slug', $slug)->firstOrFail();
        $language = $lang;

        // Set the application locale
        app()->setLocale($language);

        // Get translated content
        $translation = $this->translationService->getTranslation((string) $document->id, $language);
        $translatedDocumentData = $this->translationService->getTranslatedDocumentData($document, $language);

        if (! $translation || ! $translatedDocumentData) {
            // Redirect to create translation form if translation doesn't exist
            return redirect()->route('prezet.translate.create', [
                'slug' => $slug,
                'lang' => $language,
            ])->with('info', __('No :language translation found. Please create one.', ['language' => strtoupper($language)]));
        }

        // Parse markdown content
        $html = \Prezet\Prezet\Prezet::parseMarkdown($translation->content)->getContent();

        $linkedData = json_encode(\Prezet\Prezet\Prezet::getLinkedData($translatedDocumentData), JSON_UNESCAPED_SLASHES);
        $headings = \Prezet\Prezet\Prezet::getHeadings($html);
        $authorKey = $translatedDocumentData->frontmatter->author;
        $author = config('prezet.authors.'.$authorKey, null);

        return view('prezet.show', [
            'document' => $translatedDocumentData,
            'linkedData' => $linkedData,
            'headings' => $headings,
            'body' => $html,
            'author' => $author,
        ]);
    }

    public function create(Request $request, string $slug): View
    {
        $document = Document::where('slug', $slug)->firstOrFail();
        $language = $request->input('lang', 'vi');

        return view('prezet.translate', [
            'document' => $document,
            'language' => $language,
        ]);
    }

    public function store(Request $request, string $slug)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'language' => 'required|string|size:2',
        ]);

        $document = Document::where('slug', $slug)->firstOrFail();

        $translation = $this->translationService->translateDocument(
            $document,
            $request->language,
            $request->title,
            $request->content
        );

        // Update excerpt separately since it's not handled in translateDocument
        if ($request->excerpt) {
            $translation->excerpt = $request->excerpt;
            $translation->save();
        }

        return redirect()->route('prezet.translate.show', [
            'slug' => $slug, // Use original slug, not translated slug
            'lang' => $translation->language,
        ])->with('success', __('Translation created successfully!'));
    }
}

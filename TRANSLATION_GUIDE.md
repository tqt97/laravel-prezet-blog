# 🌍 Translation System Guide

This guide explains how to use the translation system for your Laravel Prezet blog to translate markdown content to Vietnamese and other languages.

## 📋 Overview

The translation system **allows** you to:

- Translate markdown content to multiple languages
- Store translations in a database for efficient querying
- Provide language switchers for users
- Import translations from markdown files
- Manage translations through a web interface
- Switch between languages with URL-based routing
- Use Laravel's built-in localization system

## 🗄️ Database Structure

### Translations Table

The system uses a `translations` table with the following structure:

```sql
CREATE TABLE translations (
    id INTEGER PRIMARY KEY,
    document_key VARCHAR NOT NULL,  -- References document ID (string)
    language VARCHAR(10) NOT NULL,  -- Language code (vi, en, fr, etc.)
    title VARCHAR NOT NULL,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    slug VARCHAR NOT NULL,
    frontmatter JSON,
    is_published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Indexes for performance
CREATE INDEX idx_translations_document_language ON translations(document_key, language);
CREATE INDEX idx_translations_language_published ON translations(language, is_published);
```

## 🚀 Getting Started

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Create a Translation

#### Option A: Using the Web Interface

1. Navigate to any article page
2. Click on the language switcher in the header or visit `/article-slug/translate`
3. Fill in the translated content (title, excerpt, content)
4. Save the translation

#### Option B: Using Markdown Files

1. Create a translation file with the naming convention: `original-slug-{language}.md`
2. Example: `laravel-routing-vi.md` for Vietnamese translation of `laravel-routing.md`
3. Include the translated frontmatter and content
4. Import using the command:

```bash
php artisan translate:import --language=vi
```

### 3. View Translations

Visit `/article-slug/translate/vi` to view the Vietnamese translation of an article.

## 📝 Translation File Format

### Example Vietnamese Translation File

```markdown
---
title: Hiểu về Routing trong Laravel
date: 2024-01-20
excerpt: Khám phá cách Laravel xử lý các request đến bằng hệ thống routing linh hoạt.
image: /prezet/img/ogimages/laravel-routing.webp
author: jane
tags: [routing, http, controllers]
language: vi
original_language: en
---

Routing trong Laravel định nghĩa cách ứng dụng của bạn phản hồi với các HTTP request và URL khác nhau...

## Routing Cơ Bản

Các route cơ bản nhất chấp nhận một URI và một closure hoặc controller action...
```

### Frontmatter Requirements

- `title`: Translated title (required)
- `excerpt`: Translated excerpt (optional)
- `language`: Target language code (required)
- `original_language`: Source language code (optional, defaults to 'en')
- Other fields: Copy from original or translate as needed

## 🛠️ Available Commands

### Import Translations

```bash
# Import all Vietnamese translations
php artisan translate:import --language=vi

# Import from custom path
php artisan translate:import --language=vi --path=prezet/content/translations

# Import specific language
php artisan translate:import --language=fr
```

### Bulk Translation Operations

```bash
# Translate all published documents to Vietnamese
php artisan translate:content --all --language=vi

# Translate specific document
php artisan translate:content --document=laravel-routing --language=vi

# Dry run to see what would be translated
php artisan translate:content --all --language=vi --dry-run
```

## 🌐 Language Support

### Supported Language Codes

- `vi` - Vietnamese
- `en` - English
- `fr` - French

### Language Files

The system includes JSON language files for UI translations:

- `lang/en.json` - English UI translations
- `lang/vi.json` - Vietnamese UI translations
- `lang/fr.json` - French UI translations

### Adding New Languages

1. Create a new language file: `lang/{language_code}.json`
2. Add UI translations to the file
3. Update the language switcher components
4. Create translation files with the new language code
5. Import translations using the command

## 🔧 Configuration

### Translation Service

The `TranslationService` class handles all translation operations:

```php
use App\Services\TranslationService;

$translationService = app(TranslationService::class);

// Get translation
$translation = $translationService->getTranslation($documentId, 'vi');

// Get available languages
$languages = $translationService->getAvailableLanguages($documentId);

// Translate a document
$translation = $translationService->translateDocument($document, 'vi', $title, $content);
```

### Routes

The system adds the following routes in `routes/prezet.php`:

```php
// Translation routes - must come before the catch-all route
Route::get('/{slug}/translate', [TranslationController::class, 'create'])
    ->name('prezet.translate.create')
    ->where('slug', '.*');

Route::post('/{slug}/translate', [TranslationController::class, 'store'])
    ->name('prezet.translate.store')
    ->where('slug', '.*');

Route::get('/{slug}/translate/{lang}', [TranslationController::class, 'translate'])
    ->name('prezet.translate.show')
    ->where('slug', '.*');
```

### Middleware

The `SetLocale` middleware automatically sets the application locale based on URL parameters:

```php
// Automatically sets app()->setLocale() based on route parameters
Route::middleware([SetLocale::class])->group(function () {
    // Translation routes here
});
```

## 🎨 Frontend Components

### Global Language Switcher (Header)

The header includes a global language switcher with dropdown:

```blade
<!-- In resources/views/components/prezet/header.blade.php -->
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center cursor-pointer space-x-1 px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
        <span>{{ strtoupper(app()->getLocale()) }}</span>
    </button>
    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
        <!-- Language options -->
    </div>
</div>
```

### Language Switcher Component

The language switcher component shows available translations:

```blade
<!-- In resources/views/components/prezet/language-switcher.blade.php -->
<x-prezet.language-switcher
    :document-key="(string) $document->id"
    :current-language="app()->getLocale()"
/>
```

### Translation Form

The translation form includes:

- Title input
- Excerpt textarea
- Markdown content editor with live preview using `marked.js`
- Language selection

## 📊 Database Queries

### Finding Translations

```php
// Get all Vietnamese translations
$translations = Translation::where('language', 'vi')
    ->where('is_published', true)
    ->get();

// Get translations for specific document
$translations = Translation::where('document_key', $documentId)
    ->where('is_published', true)
    ->get();

// Get available languages for a document
$languages = Translation::where('document_key', $documentId)
    ->where('is_published', true)
    ->pluck('language')
    ->toArray();
```

### Performance Optimization

The translations table includes indexes for:

- `(document_key, language)` - Fast lookups
- `(language, is_published)` - Filtered queries

## 🔄 Workflow Examples

### Example 1: Manual Translation via Web Interface

1. Visit: `/my-article/translate`
2. Fill in translated content (title, excerpt, content)
3. Save translation
4. View at: `/my-article/translate/vi`

### Example 2: Import from Markdown Files

1. Create translation file: `my-article-vi.md`
2. Add translated content with proper frontmatter
3. Import: `php artisan translate:import --language=vi`
4. View at: `/my-article/translate/vi`

### Example 3: Bulk Translation

1. Prepare translation files for all articles
2. Run: `php artisan translate:import --language=vi`
3. All translations are now available

### Example 4: Language Switching

1. User visits: `/my-article` (English)
2. Clicks language switcher → Vietnamese
3. Redirects to: `/my-article/translate/vi`
4. If translation doesn't exist, redirects to create form

## 🐛 Troubleshooting

### Common Issues

1. **Translation not found**: Check if the original document exists
2. **Import fails**: Verify file naming convention and frontmatter format
3. **Language switcher not showing**: Ensure translations are published
4. **404 errors**: Check route order in `routes/prezet.php`
5. **Undefined variables**: Ensure `$errors` variable is available in views

### Debug Commands

```bash
# Check available translations
php artisan tinker --execute="dd(App\Models\Translation::all()->toArray())"

# Check specific document translations
php artisan tinker --execute="dd(App\Models\Translation::where('document_key', '1')->get()->toArray())"

# Check current locale
php artisan tinker --execute="dd(app()->getLocale())"

# List all routes
php artisan route:list --name=prezet
```

### Error Solutions

1. **"Undefined variable $errors"**: Use `@if (isset($errors) && $errors->has())` instead of `@error`
2. **"Document key must be string"**: Use `(string) $document->id` instead of `$document->key`
3. **"404 on translation routes"**: Ensure translation routes come before catch-all route
4. **"Frontmatter type error"**: Cast FrontmatterData to array: `(array) $frontmatter`

## 📈 Current Features

### ✅ Implemented Features

- Database structure for translations
- Translation service with caching
- Translation controller for web management
- Translation routes and middleware
- Translation form with markdown preview
- Language switcher component
- Artisan commands for bulk translation and import
- Integration with Laravel's locale system
- Global language switcher in header
- Vietnamese, French, and English language files
- SetLocale middleware for URL-based language switching

### 🔄 Planned Enhancements

See `TRANSLATION_IMPROVEMENTS.md` for a comprehensive list of future improvements including:

- Translation status indicators
- Better language switcher design
- SEO optimization with hreflang tags
- Translation management dashboard
- Auto-translation integration
- Performance optimizations

## 📚 Best Practices

1. **Consistent Naming**: Use consistent language codes and file naming
2. **Quality Control**: Review translations before publishing
3. **SEO Optimization**: Translate meta descriptions and titles
4. **Content Structure**: Maintain the same markdown structure
5. **Regular Updates**: Keep translations in sync with original content
6. **Route Order**: Place translation routes before catch-all routes
7. **Error Handling**: Use proper error checking in views
8. **Locale Management**: Use `app()->setLocale()` consistently

## 🤝 Contributing

To contribute to the translation system:

1. Follow the existing code structure
2. Add tests for new features
3. Update documentation
4. Use consistent naming conventions
5. Check for linter errors before committing

## 📄 License

This translation system is part of the Laravel Prezet blog and follows the same license terms.

---

_Last updated: August 2025_
_Version: 1.0_

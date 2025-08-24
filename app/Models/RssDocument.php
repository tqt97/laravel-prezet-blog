<?php

namespace App\Models;

use Prezet\Prezet\Models\Document as DocumentModel;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class RssDocument extends DocumentModel implements Feedable
{
    public $table = 'documents';

    public static function getAllFeedItems()
    {
        return self::query()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function toFeedItem(): FeedItem
    {
        $authors = config('prezet.authors');
        $author = $authors[$this->frontmatter->author]['name'] ?? 'Unknown';

        return FeedItem::create()
            ->id($this->id)
            ->title($this->frontmatter->title)
            ->summary($this->frontmatter->excerpt)
            ->updated($this->created_at)
            ->link(route('prezet.show', $this->slug))
            ->authorName($author);
    }
}

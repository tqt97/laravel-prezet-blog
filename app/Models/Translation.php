<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_key',
        'language',
        'title',
        'excerpt',
        'content',
        'slug',
        'frontmatter',
        'is_published',
    ];

    protected $casts = [
        'frontmatter' => 'array',
        'is_published' => 'boolean',
    ];

    public function document()
    {
        return $this->belongsTo(\Prezet\Prezet\Models\Document::class, 'document_key', 'id');
    }
}

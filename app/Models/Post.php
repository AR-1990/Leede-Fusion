<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'category',
        'cover_image',
        'excerpt',
        'body',
        'meta_title',
        'meta_description',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /** Tags the rich-text editor produces; everything else is stripped on save. */
    private const ALLOWED_TAGS = '<p><br><h2><h3><h4><strong><b><em><i><u><s><a><ul><ol><li><blockquote><img><figure><figcaption><span><hr><pre><code>';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->orderByDesc('published_at');
    }

    public function isLive(): bool
    {
        return $this->is_published && (! $this->published_at || $this->published_at->isPast());
    }

    public function setBodyAttribute(?string $html): void
    {
        $this->attributes['body'] = self::sanitize($html ?? '');
    }

    public static function sanitize(string $html): string
    {
        $html = preg_replace('#<(script|style|iframe|object|embed)[^>]*>.*?</\1>#is', '', $html);
        $html = strip_tags($html, self::ALLOWED_TAGS);
        // Drop inline event handlers and javascript: URLs.
        $html = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);

        return preg_replace('/(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '$1="#"', $html);
    }

    public static function uniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source) ?: 'post';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function getReadTimeAttribute(): string
    {
        $words = str_word_count(strip_tags($this->body ?? ''));

        return max(1, (int) ceil($words / 200)).' min read';
    }

    public function getSummaryAttribute(): string
    {
        return $this->excerpt ?: Str::limit(trim(strip_tags($this->body ?? '')), 160);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ContentItem;
use Illuminate\Support\Str;

class NewsPageController extends Controller
{
    public function __invoke(string $slug)
    {
        $article = ContentItem::query()
            ->where('type', 'news')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $metadata = $article->metadata ?? [];
        $plainText = html_entity_decode(strip_tags($article->description ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $plainText = trim(preg_replace('/\s+/u', ' ', $plainText) ?? '');
        $description = $metadata['seo_description'] ?? Str::limit($plainText ?: ($article->subtitle ?: 'Berita terbaru dari 13 Nadi Musik.'), 160);
        $title = $metadata['seo_title'] ?? (Str::limit($article->title, 48, '') . ' | 13 Nadi Musik');
        $canonical = url('/berita/' . $article->slug);
        $image = $metadata['seo_image'] ?? $article->image_url;
        $image = $image ? url($image) : null;
        $publishedAt = $article->created_at?->toIso8601String();
        $modifiedAt = $article->updated_at?->toIso8601String();

        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonical],
            'headline' => $article->title,
            'description' => $description,
            'datePublished' => $publishedAt,
            'dateModified' => $modifiedAt,
            'author' => ['@type' => 'Organization', 'name' => '13 Nadi Musik', 'url' => url('/')],
            'publisher' => [
                '@type' => 'Organization',
                'name' => '13 Nadi Records',
                'logo' => ['@type' => 'ImageObject', 'url' => url('/logo.png')],
            ],
        ];
        if ($image) $structuredData['image'] = [$image];

        return view('app', [
            'seo' => [
                'title' => $title,
                'description' => $description,
                'canonical' => $canonical,
                'image' => $image,
                'published_at' => $publishedAt,
                'modified_at' => $modifiedAt,
                'article' => $article,
                'structured_data' => $structuredData,
            ],
        ]);
    }
}

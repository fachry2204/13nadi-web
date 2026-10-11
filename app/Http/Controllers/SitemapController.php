<?php

namespace App\Http\Controllers;

use App\Models\ContentItem;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $staticPaths = ['/', '/rilis', '/berita', '/foto', '/video', '/tentang', '/mugen'];
        $staticEntries = collect($staticPaths)->map(fn (string $path) => [url($path), null]);
        $newsEntries = ContentItem::query()
            ->where('type', 'news')
            ->where('is_active', true)
            ->whereNotNull('slug')
            ->get(['slug', 'updated_at'])
            ->map(fn (ContentItem $article) => [url('/berita/' . $article->slug), $article->updated_at]);

        $entries = $staticEntries->concat($newsEntries);
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($entries as [$location, $updatedAt]) {
            $xml .= '  <url><loc>' . e($location) . '</loc>';
            if ($updatedAt) $xml .= '<lastmod>' . $updatedAt->toAtomString() . '</lastmod>';
            $xml .= "</url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}

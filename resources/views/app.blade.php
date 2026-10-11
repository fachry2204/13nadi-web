<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="{{ $seo['description'] ?? '13 Nadi Records — rumah bagi karya autentik dan suara baru dari Indonesia.' }}" />
    @if (isset($seo['canonical']))
      <link rel="canonical" href="{{ $seo['canonical'] }}" />
    @endif
    <meta property="og:type" content="{{ isset($seo['article']) ? 'article' : 'website' }}" />
    <meta property="og:title" content="{{ $seo['title'] ?? '13 Nadi Records' }}" />
    <meta property="og:description" content="{{ $seo['description'] ?? 'Nada yang menghubungkan cerita.' }}" />
    <meta property="og:url" content="{{ $seo['canonical'] ?? url('/') }}" />
    @if (!empty($seo['image']))
      <meta property="og:image" content="{{ $seo['image'] }}" />
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:image" content="{{ $seo['image'] }}" />
    @endif
    @if (isset($seo['published_at']))
      <meta property="article:published_time" content="{{ $seo['published_at'] }}" />
      <meta property="article:modified_time" content="{{ $seo['modified_at'] }}" />
    @endif
    <meta name="theme-color" content="#08a9e6" />
    <title>{{ $seo['title'] ?? '13 Nadi Records — Nada yang Menghubungkan Cerita' }}</title>
    @isset($seo['structured_data'])
      <script type="application/ld+json">@json($seo['structured_data'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
    @endisset
    @vite(['resources/js/main.ts'])
  </head>
  <body>
    <div id="root">
      @isset($seo['article'])
        <main class="seo-article-fallback">
          <article>
            <span>{{ $seo['article']->subtitle ?: 'Berita 13 Nadi Musik' }}</span>
            <h1>{{ $seo['article']->title }}</h1>
            @if ($seo['article']->image_url)
              <img src="{{ url($seo['article']->image_url) }}" alt="{{ $seo['article']->title }}" />
            @endif
            <div>{!! $seo['article']->description !!}</div>
          </article>
        </main>
      @endisset
    </div>
  </body>
</html>

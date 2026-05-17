<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-PNNHB8LH4K"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-PNNHB8LH4K');
</script>

@inject('settings', 'App\Services\SettingService')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="google-site-verification" content="w2VijygYi0N-dJzLst6_6XwTUwn3gx3Am9OWf2dfAdg" />
<title>{{ $title ?? 'NCS Hindi | Premium Royalty-Free Hindi Music & Soundtracks' }}</title>
    
    {{-- Search Engine Optimization (SEO) Meta Tags --}}
    <meta name="description" content="{{ $description ?? 'NCS Hindi is the ultimate hub for premium, royalty-free Hindi music, non-copyright soundtracks, and studio-grade audio assets for content creators.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'ncs hindi, royalty free music, non copyright music, non copyright hindi music, creator music, hindi background music, royalty free sound effects' }}">
    <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">
    <meta name="robots" content="{{ $robots ?? 'index, follow' }}">

    {{-- Open Graph (OG) Meta Tags (Facebook, LinkedIn, Discord, etc.) --}}
    <meta property="og:site_name" content="NCS Hindi">
    <meta property="og:title" content="{{ $title ?? 'NCS Hindi | Premium Royalty-Free Hindi Music & Soundtracks' }}">
    <meta property="og:description" content="{{ $description ?? 'Discover and download premium, studio-grade Hindi NCS music assets. Perfect for YouTube videos, streams, and content creation.' }}">
    <meta property="og:image" content="{{ $ogImage ?? $settings->getImageUrl('logo') ?: $settings->getImageUrl('favicon') }}">
    <meta property="og:url" content="{{ $canonicalUrl ?? url()->current() }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">

    {{-- Twitter Cards Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'NCS Hindi | Premium Royalty-Free Hindi Music & Soundtracks' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Discover and download premium, studio-grade Hindi NCS music assets. Perfect for YouTube videos, streams, and content creation.' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? $settings->getImageUrl('logo') ?: $settings->getImageUrl('favicon') }}">

    {{-- Schema.org Structured Data (JSON-LD) for Search Engine Rich Snippets --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "NCS Hindi",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/search-all') }}?query={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <link rel="icon" type="image/x-icon" href="{{ $settings->getImageUrl('favicon') }}">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@stack('heads')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@700;900&display=swap');

    :root {
        --bg: #050505;
        --card: #0d0d0f;
        --border: #1a1a1e;
        --grad-primary: linear-gradient(135deg, #b45309 0%, #991b1b 100%);
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg);
        color: #efefef;
    }

    .font-brand {
        font-family: 'Outfit', sans-serif;
    }

    .btn-vault {
        background: var(--grad-primary);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(153, 27, 27, 0.4);
        border-radius: 12px;
        color: white;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .forum-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 24px;
        transition: 0.3s;
    }

    .forum-card:hover {
        border-color: #b45309;
        background: #111114;
    }

    .stat-badge {
        background: rgba(180, 83, 9, 0.1);
        border: 1px solid rgba(180, 83, 9, 0.2);
        color: #f59e0b;
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
</style>








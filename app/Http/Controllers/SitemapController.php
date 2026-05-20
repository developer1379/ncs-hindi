<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\ForumThread;
use App\Models\Music;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];
        $now = now()->toAtomString();

        // 1. Static Pages
        $staticPages = [
            ['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => route('webapp.streams'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => route('webapp.trending'), 'priority' => '0.8', 'changefreq' => 'hourly'],
            ['loc' => route('webapp.faq'), 'priority' => '0.5', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $urls[] = [
                'loc' => $page['loc'],
                'lastmod' => $now,
                'changefreq' => $page['changefreq'],
                'priority' => $page['priority'],
            ];
        }

        // 2. Music music
        $music = Music::where('is_public', true)->latest()->get();
        foreach ($music as $music) {
            $urls[] = [
                'loc' => route('webapp.music.show', $music->slug),
                'lastmod' => $music->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        // 3. Blogs (safely skipped as there are no public frontend routes for blogs yet)
        // $blogs = Blog::where('status', 'published')->latest()->get();

        // 4. Category / Genre Pages
        $categories = \App\Models\Category::where('is_active', 1)->get();
        foreach ($categories as $category) {
            $urls[] = [
                'loc' => route('webapp.music.genre', $category->slug),
                'lastmod' => $category->updated_at ? $category->updated_at->toAtomString() : $now,
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        // 5. Forum Threads
        $threads = ForumThread::latest()->get();
        foreach ($threads as $thread) {
            $urls[] = [
                'loc' => route('webapp.forum.show', $thread->slug),
                'lastmod' => $thread->updated_at->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.6',
            ];
        }

        return response()->view('sitemap', compact('urls'))->header('Content-Type', 'text/xml');
    }
}








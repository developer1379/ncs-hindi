<?php

namespace App\Http\Controllers;

use App\Models$musiclog;
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

        // 3. Blogs (if any)
        $blogs = Blog::where('status', 'published')->latest()->get();
        foreach ($blogs as $blog) {
            // Assuming blog route is 'webapp.blogs.show' if it exists, or similar
            // Since I don't see frontend blog routes, I'll skip for now or use a placeholder if I find it
            // $urls[] = [
            //     'loc' => route('webapp.blogs.show', $blog->slug),
            //     'lastmod' => $blog->updated_at->toAtomString(),
            //     'changefreq' => 'weekly',
            //     'priority' => '0.7',
            // ];
        }

        // 4. Forum Threads
        $threads = ForumThread::latest()->get();
        foreach ($threads as $thread) {
            $urls[] = [
                'loc' => route('forum.show', $thread->slug),
                'lastmod' => $thread->updated_at->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.6',
            ];
        }

        return response()->view('sitemap', compact('urls'))->header('Content-Type', 'text/xml');
    }
}








<?php

namespace App\Http\Controllers\Webapp;

use App\Http\Controllers\Controller;
use App\Models\ForumThread;
use App\Models\MusicStem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (strlen($query) < 3) {
            return response()->json(['threads' => [], 'stems' => []]);
        }

        // Search Forum Threads
        $threads = ForumThread::where('title', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->whereNull('deleted_at')
            ->limit(5)
            ->get(['id', 'title', 'slug']);

        // Search Music Stems
        $stems = MusicStem::where('title', 'LIKE', "%{$query}%")
            ->orWhere('artist_name', 'LIKE', "%{$query}%")
            ->orWhere('tags_keywords', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id', 'title', 'slug', 'artist_name', 'featured_image']);

        return response()->json([
            'threads' => $threads,
            'stems' => $stems
        ]);
    }

    public function index(Request $request)
    {
        $query = $request->get('query');

        // Fetch Forum Threads
        $threads = DB::table('forum_threads')
            ->whereNull('deleted_at')
            ->where('title', 'LIKE', "%{$query}%")
            ->paginate(10, ['*'], 'threads_page');

        // Fetch Music Stems
        $stems = DB::table('music_stems')
            ->where('title', 'LIKE', "%{$query}%")
            ->paginate(12, ['*'], 'stems_page');


        return view('webapp.search.index', compact('threads', 'stems', 'query'));
    }
}

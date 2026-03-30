<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use App\Models\StemInteraction;
use App\Repositories\Contracts\StemRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\MusicStem;

class StemController extends Controller
{
    protected $stemRepo;

    public function __construct(StemRepositoryInterface $stemRepo)
    {
        $this->stemRepo = $stemRepo;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'category_id' => $request->category,
            'sort' => $request->sort ?? 'latest'
        ];

        $stems = $this->stemRepo->getLibraryStems($filters);

        return view('webapp.streams', compact('stems'));
    }

    public function show($slug)
    {
        $stem = MusicStem::with('category')
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        if (Auth::check()) {
            $this->stemRepo->logInteraction($stem->id, Auth::id(), 'view');
        }

        return view('webapp.stems_show', compact('stem'));
    }

    public function download($id)
    {
        $stem = MusicStem::findOrFail($id);

        if (Auth::check()) {
            $this->stemRepo->logInteraction($id, Auth::id(), 'download');
        }

        if ($stem->mega_link && filter_var($stem->mega_link, FILTER_VALIDATE_URL)) {
            return redirect()->away($stem->mega_link);
        }

        if ($stem->file_path && filter_var($stem->file_path, FILTER_VALIDATE_URL)) {
            return redirect()->away($stem->file_path);
        }

        if (!$stem->file_path) {
            abort(404);
        }

        $downloadName = $stem->file_name ?: basename($stem->file_path);

        return Storage::disk('public')->download($stem->file_path, $downloadName);
    }

    public function toggleLike($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        return DB::transaction(function () use ($id) {
            $userId = Auth::id();
            $stem = MusicStem::lockForUpdate()->findOrFail($id);

            $interaction = StemInteraction::where('user_id', $userId)
                ->where('stem_id', $id)
                ->where('type', 'like')
                ->first();

            if ($interaction) {
                $interaction->delete();

                if ($stem->like_count > 0) {
                    $stem->decrement('like_count');
                }

                $stem->refresh();

                return response()->json([
                    'liked' => false,
                    'count' => $stem->like_count,
                    'message' => 'Removed from your likes.',
                ]);
            }

            StemInteraction::create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'user_id' => $userId,
                'stem_id' => $id,
                'type' => 'like',
                'created_at' => now(),
            ]);

            $stem->increment('like_count');
            $stem->refresh();

            return response()->json([
                'liked' => true,
                'count' => $stem->like_count,
                'message' => 'Added to your likes.',
            ]);
        });
    }

    public function store(Request $request, $threadId)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'stem_file'      => 'required|file|mimes:zip,wav,mp3|max:102400',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bpm'            => 'nullable|integer',
            'music_key'      => 'nullable|string',
        ]);

        $this->stemRepo->uploadStem($threadId, $data);

        return back()->with('success', 'Studio asset published to the Vault!');
    }
}

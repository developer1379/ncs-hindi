<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ForumThread;
use App\Models\Music;
use App\Models\MusicInteraction;
use App\Models\User;
use App\Repositories\Contracts\ForumRepositoryInterface;
use App\Repositories\Contracts\ProfileRepositoryInterface;
use App\Repositories\Contracts\MusicRepositoryInterface;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PageController extends Controller
{
    protected $forumRepo;
    protected $musicRepo;
    protected $profileRepo;
    protected $settingService;

    public function __construct(
        ForumRepositoryInterface $forumRepo,
        MusicRepositoryInterface $musicRepo,
        ProfileRepositoryInterface $profileRepo,
        SettingService $settingService
    ) {
        $this->forumRepo = $forumRepo;
        $this->musicRepo = $musicRepo;
        $this->profileRepo = $profileRepo;
        $this->settingService = $settingService;
    }

    public function index()
    {
        $posts = $this->forumRepo->getAllThreads();
        return view('webapp.index', compact('posts'));
    }

    public function trending(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'category_id' => $request->get('category'),
            'sort' => $request->get('sort', 'downloads'),
            'per_page' => $request->get('per_page', 9),
        ];

        $trendingStems = $this->musicRepo->getTrendingMusic($filters);
        $featuredStem = $this->musicRepo->getTrendingSpotlight($filters);
        $topCreators = $this->musicRepo->getTrendingCreators($filters);
        $trendingStats = $this->musicRepo->getTrendingStats($filters);
        $categories = Category::where('is_active', 1)
            ->withCount([
                'music as stems_count' => function ($query) use ($filters) {
                    $query->where('is_public', true);
                    if (!empty($filters['search'])) {
                        $search = $filters['search'];
                        $query->where(function ($q) use ($search) {
                            $q->where('title', 'LIKE', "%{$search}%")
                                ->orWhere('artist_name', 'LIKE', "%{$search}%")
                                ->orWhere('album_movie_name', 'LIKE', "%{$search}%")
                                ->orWhere('tags_keywords', 'LIKE', "%{$search}%");
                        });
                    }
                },
                'music as music_count' => function ($query) use ($filters) {
                    $query->where('is_public', true);
                    if (!empty($filters['search'])) {
                        $search = $filters['search'];
                        $query->where(function ($q) use ($search) {
                            $q->where('title', 'LIKE', "%{$search}%")
                                ->orWhere('artist_name', 'LIKE', "%{$search}%")
                                ->orWhere('album_movie_name', 'LIKE', "%{$search}%")
                                ->orWhere('tags_keywords', 'LIKE', "%{$search}%");
                        });
                    }
                }
            ])
            ->orderBy('name')
            ->get();

        return view('webapp.trending', compact(
            'trendingStems',
            'featuredStem',
            'topCreators',
            'trendingStats',
            'categories',
            'filters'
        ));
    }

    public function streams()
    {
        $music = $this->musicRepo->getLibraryMusic();
        return view('webapp.streams', compact('music'));
    }

    public function profile()
    {
        $user = User::with('profile')->findOrFail(auth()->id());
        $profile = $user->profile;

        $likedSongs = $this->getUserStemActivity($user->id, 'like', 6);
        $viewedSongs = $this->getUserStemActivity($user->id, 'view', 6);
        $downloadedSongs = $this->getUserStemActivity($user->id, 'download', 6);

        $profileStats = [
            'liked' => MusicInteraction::query()
                ->where('user_id', $user->id)
                ->where('type', 'like')
                ->select('stem_id')
                ->distinct()
                ->count('stem_id'),
            'viewed' => MusicInteraction::query()
                ->where('user_id', $user->id)
                ->where('type', 'view')
                ->select('stem_id')
                ->distinct()
                ->count('stem_id'),
            'downloaded' => MusicInteraction::query()
                ->where('user_id', $user->id)
                ->where('type', 'download')
                ->select('stem_id')
                ->distinct()
                ->count('stem_id'),
            'uploads' => ForumThread::query()->where('user_id', $user->id)->count(),
        ];

        return view('webapp.profile.index', compact(
            'user',
            'profile',
            'likedSongs',
            'viewedSongs',
            'downloadedSongs',
            'profileStats'
        ));
    }

    public function faq()
    {
        $page = [
            'title' => $this->settingService->get('faq_page_title', 'FAQ / Legal Guides'),
            'intro' => $this->settingService->get('faq_page_intro', 'Answers to common questions and the rules that apply when using NCS Hindi music.'),
            'faq_content' => $this->settingService->get('faq_page_content', ''),
            'legal_title' => $this->settingService->get('legal_page_title', 'Legal Guides'),
            'legal_intro' => $this->settingService->get('legal_page_intro', 'Simple usage guidance for creators, brands, and community members.'),
            'legal_content' => $this->settingService->get('legal_page_content', ''),
        ];

        return view('webapp.faq', compact('page'));
    }

    public function editProfile()
    {
        $profile = $this->profileRepo->findByUserId(auth()->id());
        return view('webapp.profile.edit', compact('profile'));
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'studio_name'   => 'required|string|max:255',
            'bio'           => 'nullable|string|max:1000',
            'website_url'   => 'nullable|url',
            'instagram_url' => 'nullable|string',
        ]);

        $this->profileRepo->updateProfile(auth()->id(), $data);

        return redirect()->route('webapp.profile')
            ->with('success', 'Profile updated successfully!');
    }

    public function show($slug)
    {
        // Eager load 'author' and the author's 'profile' relationship
        $post = ForumThread::with(['author.profile'])
            ->where('slug', $slug)
            ->firstOrFail(); // Automatically throws a 404 if not found

        return view('webapp.forum.show', compact('post'));
    }

    public function showForum($id)
    {
        $post = $this->forumRepo->findThreadById($id);

        return view('webapp.forum.show', compact('post'));
    }
    public function createThread()
    {
        $categories = Category::where('is_active', 1)->get();
        return view('webapp.forum.create', compact('categories'));
    }
    public function storeThread(Request $request)
    {
        $validData = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content'     => 'required|string',
        ]);
        $thread = $this->forumRepo->storeThread($validData);
        return redirect()->route('home')->with('success', 'Post published to the Vault!');
    }

    public function uploadEditorImage(Request $request, \App\Services\ImgBBService $imgBBService)
    {
        $request->validate([
            'file' => 'required|image|max:10240', // 10MB max
        ]);

        if ($request->hasFile('file')) {
            $url = $imgBBService->upload($request->file('file'));

            if ($url) {
                return response()->json([
                    'location' => $url
                ]);
            }
        }

        return response()->json(['error' => 'Image upload failed.'], 400);
    }

    private function getUserStemActivity(string $userId, string $type, int $limit = 6): Collection
    {
        $stemIds = MusicInteraction::query()
            ->where('user_id', $userId)
            ->where('type', $type)
            ->selectRaw('stem_id, MAX(created_at) as last_activity_at')
            ->groupBy('stem_id')
            ->orderByDesc('last_activity_at')
            ->limit($limit)
            ->pluck('stem_id');

        if ($stemIds->isEmpty()) {
            return collect();
        }

        $music = Music::query()
            ->with('category')
            ->whereIn('id', $stemIds)
            ->get()
            ->keyBy('id');

        return $stemIds->map(fn ($musicId) => $music->get($musicId))->filter()->values();
    }
}








<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ForumThread;
use App\Models\User;
use App\Repositories\Contracts\ForumRepositoryInterface;
use App\Repositories\Contracts\ProfileRepositoryInterface;
use App\Repositories\Contracts\StemRepositoryInterface;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $forumRepo;
    protected $stemRepo;
    protected $profileRepo;

    public function __construct(
        ForumRepositoryInterface $forumRepo,
        StemRepositoryInterface $stemRepo,
        ProfileRepositoryInterface $profileRepo
    ) {
        $this->forumRepo = $forumRepo;
        $this->stemRepo = $stemRepo;
        $this->profileRepo = $profileRepo;
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

        $trendingStems = $this->stemRepo->getTrendingStems($filters);
        $featuredStem = $this->stemRepo->getTrendingSpotlight($filters);
        $topCreators = $this->stemRepo->getTrendingCreators($filters);
        $trendingStats = $this->stemRepo->getTrendingStats($filters);
        $categories = Category::where('is_active', 1)
            ->withCount(['stems' => function ($query) use ($filters) {
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
            }])
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
        $stems = $this->stemRepo->getLibraryStems();
        return view('webapp.streams', compact('stems'));
    }

    public function profile()
    {
        $user = User::with('profile')->findOrFail(auth()->id());

        $recent_uploads = [];

        return view('webapp.profile.index', compact('user', 'recent_uploads'));
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
            ->with('success', 'Studio updated successfully!');
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
}

<x-webapp-layout title="Trending Releases | NCS Hindi">
    @push('heads')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <style>
            .trend-shell {
                background:
                    radial-gradient(circle at top left, rgba(180, 83, 9, 0.18), transparent 28%),
                    radial-gradient(circle at top right, rgba(153, 27, 27, 0.18), transparent 24%),
                    linear-gradient(180deg, rgba(8, 8, 10, 0.98), rgba(4, 4, 5, 1));
            }

            .glass-panel {
                background: rgba(12, 12, 15, 0.78);
                border: 1px solid rgba(255, 255, 255, 0.06);
                box-shadow: 0 24px 80px rgba(0, 0, 0, 0.32);
                backdrop-filter: blur(16px);
            }

            .soft-border {
                border: 1px solid rgba(255, 255, 255, 0.06);
            }

            .trend-card {
                background: linear-gradient(180deg, rgba(16, 16, 20, 0.98), rgba(9, 9, 11, 0.98));
                border: 1px solid rgba(255, 255, 255, 0.06);
                box-shadow: 0 18px 60px rgba(0, 0, 0, 0.28);
                transition: transform 180ms ease, border-color 180ms ease, box-shadow 180ms ease;
            }

            .trend-card:hover {
                transform: translateY(-4px);
                border-color: rgba(245, 158, 11, 0.35);
                box-shadow: 0 24px 80px rgba(0, 0, 0, 0.36);
            }

            .rank-badge {
                background: linear-gradient(135deg, rgba(245, 158, 11, 0.95), rgba(153, 27, 27, 0.95));
                color: #fff;
                box-shadow: 0 10px 24px rgba(153, 27, 27, 0.32);
            }

            .metric-chip {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.06);
            }
        </style>
    @endpush

    @php
        $sort = $filters['sort'] ?? 'downloads';
        $featuredImage = $featuredStem?->featured_image ?: 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=1600&q=80';
    @endphp

    <section class="trend-shell relative overflow-hidden rounded-[40px] mb-10 soft-border">
        <div class="absolute inset-0 opacity-40 bg-[radial-gradient(circle_at_top_right,_rgba(245,158,11,0.16),_transparent_28%),radial-gradient(circle_at_bottom_left,_rgba(153,27,27,0.16),_transparent_26%)]"></div>
        <div class="relative grid lg:grid-cols-[1.15fr_0.85fr] gap-8 p-6 md:p-10 lg:p-14">
            <div class="space-y-8">
                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full glass-panel text-[10px] font-black uppercase tracking-[0.22em] text-amber-400">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    Trending now
                    <span class="text-zinc-500">/ dynamic vault chart</span>
                </div>

                <div>
                    <h1 class="font-brand text-4xl md:text-6xl lg:text-7xl font-black uppercase tracking-tighter text-white leading-[0.9]">
                        Discover what the vault is
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-orange-400 to-red-500">
                            playing most
                        </span>
                    </h1>
                    <p class="mt-5 max-w-2xl text-zinc-300 leading-relaxed text-sm md:text-base">
                        Live rankings, creator highlights, and instant access to the releases driving the most downloads,
                        likes, and views across the NCS Hindi vault.
                    </p>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="glass-panel rounded-3xl p-4">
                        <p class="text-[10px] uppercase tracking-[0.22em] text-zinc-500 font-black">Tracks</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ number_format($trendingStats['tracks'] ?? 0) }}</p>
                    </div>
                    <div class="glass-panel rounded-3xl p-4">
                        <p class="text-[10px] uppercase tracking-[0.22em] text-zinc-500 font-black">Downloads</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ number_format($trendingStats['downloads'] ?? 0) }}</p>
                    </div>
                    <div class="glass-panel rounded-3xl p-4">
                        <p class="text-[10px] uppercase tracking-[0.22em] text-zinc-500 font-black">Likes</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ number_format($trendingStats['likes'] ?? 0) }}</p>
                    </div>
                    <div class="glass-panel rounded-3xl p-4">
                        <p class="text-[10px] uppercase tracking-[0.22em] text-zinc-500 font-black">Views</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ number_format($trendingStats['views'] ?? 0) }}</p>
                    </div>
                </div>

                <form action="{{ route('webapp.trending') }}" method="GET" class="glass-panel rounded-[28px] p-4 md:p-5">
                    <div class="grid grid-cols-1 lg:grid-cols-[1.3fr_0.8fr_0.8fr_auto] gap-3">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-500 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, artist, album, or tags"
                                class="w-full rounded-2xl bg-black/40 soft-border pl-11 pr-4 py-4 text-sm text-white outline-none focus:border-amber-500/50">
                        </div>
                        <select name="category" class="w-full rounded-2xl bg-black/40 soft-border px-4 py-4 text-sm text-zinc-300 outline-none focus:border-amber-500/50">
                            <option value="">All categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ number_format($category->stems_count) }})
                                </option>
                            @endforeach
                        </select>
                        <select name="sort" class="w-full rounded-2xl bg-black/40 soft-border px-4 py-4 text-sm text-zinc-300 outline-none focus:border-amber-500/50">
                            <option value="downloads" {{ $sort === 'downloads' ? 'selected' : '' }}>Top downloads</option>
                            <option value="likes" {{ $sort === 'likes' ? 'selected' : '' }}>Most liked</option>
                            <option value="views" {{ $sort === 'views' ? 'selected' : '' }}>Most viewed</option>
                            <option value="newest" {{ $sort === 'newest' || $sort === 'latest' ? 'selected' : '' }}>Newest releases</option>
                        </select>
                        <div class="flex items-stretch gap-3">
                            <button type="submit" class="btn-vault px-6 py-4 rounded-2xl text-[10px] font-black tracking-[0.2em] whitespace-nowrap">
                                Filter
                            </button>
                            <a href="{{ route('webapp.trending') }}" class="px-5 py-4 rounded-2xl bg-white/5 soft-border text-[10px] font-black tracking-[0.2em] uppercase text-zinc-300 hover:text-white hover:border-amber-500/40 transition">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('webapp.trending', array_merge(request()->except('page'), ['sort' => 'downloads'])) }}"
                        class="px-4 py-2 rounded-full text-[10px] font-black tracking-[0.2em] uppercase transition {{ $sort === 'downloads' ? 'bg-amber-500 text-black' : 'bg-white/5 text-zinc-400 hover:text-white' }}">
                        Top downloads
                    </a>
                    <a href="{{ route('webapp.trending', array_merge(request()->except('page'), ['sort' => 'likes'])) }}"
                        class="px-4 py-2 rounded-full text-[10px] font-black tracking-[0.2em] uppercase transition {{ $sort === 'likes' ? 'bg-amber-500 text-black' : 'bg-white/5 text-zinc-400 hover:text-white' }}">
                        Most liked
                    </a>
                    <a href="{{ route('webapp.trending', array_merge(request()->except('page'), ['sort' => 'views'])) }}"
                        class="px-4 py-2 rounded-full text-[10px] font-black tracking-[0.2em] uppercase transition {{ $sort === 'views' ? 'bg-amber-500 text-black' : 'bg-white/5 text-zinc-400 hover:text-white' }}">
                        Most viewed
                    </a>
                    <a href="{{ route('webapp.trending', array_merge(request()->except('page'), ['sort' => 'newest'])) }}"
                        class="px-4 py-2 rounded-full text-[10px] font-black tracking-[0.2em] uppercase transition {{ $sort === 'newest' || $sort === 'latest' ? 'bg-amber-500 text-black' : 'bg-white/5 text-zinc-400 hover:text-white' }}">
                        Newest
                    </a>
                </div>
            </div>

            <div class="space-y-4">
                @if ($featuredStem)
                    <div class="relative overflow-hidden rounded-[34px] soft-border bg-black/40">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                        <img src="{{ $featuredImage }}" alt="{{ $featuredStem->title }}" class="absolute inset-0 h-full w-full object-cover opacity-70">
                        <div class="relative min-h-[420px] p-6 md:p-8 flex flex-col justify-end">
                            <div class="flex flex-wrap items-center gap-2 mb-4">
                                <span class="rank-badge px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-[0.2em]">
                                    Featured #1
                                </span>
                                @if ($featuredStem->category)
                                    <span class="px-3 py-1 rounded-full bg-black/50 soft-border text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">
                                        {{ $featuredStem->category->name }}
                                    </span>
                                @endif
                            </div>

                            <h2 class="font-brand text-3xl md:text-4xl font-black uppercase tracking-tighter text-white leading-tight">
                                {{ $featuredStem->title }}
                            </h2>
                            <p class="mt-2 text-zinc-300 text-sm">
                                {{ $featuredStem->artist_name ?: 'Unknown artist' }}
                            </p>
                            @if ($featuredStem->description)
                                <p class="mt-4 text-sm leading-relaxed text-zinc-400">
                                    {{ \Illuminate\Support\Str::limit($featuredStem->description, 130) }}
                                </p>
                            @endif

                            <div class="mt-6 flex flex-wrap gap-3">
                                <a href="{{ route('webapp.stems.show', $featuredStem->id) }}"
                                    class="btn-vault px-6 py-3 rounded-2xl text-[10px] font-black tracking-[0.2em] uppercase">
                                    View release
                                </a>
                                <a href="{{ $featuredStem->mega_link && filter_var($featuredStem->mega_link, FILTER_VALIDATE_URL) ? $featuredStem->mega_link : route('webapp.stems.download', $featuredStem->id) }}"
                                    target="_blank"
                                    class="px-6 py-3 rounded-2xl bg-white/10 soft-border text-[10px] font-black tracking-[0.2em] uppercase text-white hover:bg-white/15 transition">
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-3 gap-3">
                    <div class="metric-chip rounded-3xl p-4">
                        <p class="text-[9px] uppercase tracking-[0.2em] text-zinc-500 font-black">Most liked</p>
                        <p class="mt-2 text-lg font-black text-white">Live view</p>
                    </div>
                    <div class="metric-chip rounded-3xl p-4">
                        <p class="text-[9px] uppercase tracking-[0.2em] text-zinc-500 font-black">Created for</p>
                        <p class="mt-2 text-lg font-black text-white">Fast discovery</p>
                    </div>
                    <div class="metric-chip rounded-3xl p-4">
                        <p class="text-[9px] uppercase tracking-[0.2em] text-zinc-500 font-black">Access</p>
                        <p class="mt-2 text-lg font-black text-white">Live actions</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($categories->isNotEmpty())
        <section class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-brand text-xl md:text-2xl font-black uppercase tracking-tight text-white">Browse categories</h3>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">Quick filters</span>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('webapp.trending', array_merge(request()->except(['page', 'category']), [])) }}"
                    class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.2em] transition {{ !request('category') ? 'bg-white text-black' : 'bg-white/5 text-zinc-400 hover:text-white' }}">
                    All
                </a>
                @foreach ($categories->take(8) as $category)
                    <a href="{{ route('webapp.trending', array_merge(request()->except('page'), ['category' => $category->id])) }}"
                        class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.2em] transition {{ request('category') == $category->id ? 'bg-amber-500 text-black' : 'bg-white/5 text-zinc-400 hover:text-white' }}">
                        {{ $category->name }}
                        <span class="ml-2 text-[9px] opacity-70">{{ number_format($category->stems_count) }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mb-10">
        <div class="flex items-end justify-between gap-4 mb-5">
            <div>
                <h3 class="font-brand text-2xl md:text-3xl font-black uppercase tracking-tight text-white">
                    Trending releases
                </h3>
                <p class="mt-2 text-sm text-zinc-500">
                    Ranked by {{ $sort === 'likes' ? 'likes' : ($sort === 'views' ? 'views' : ($sort === 'newest' || $sort === 'latest' ? 'freshness' : 'downloads')) }}.
                </p>
            </div>
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">
                Showing {{ number_format($trendingStems->count()) }} of {{ number_format($trendingStems->total()) }}
            </p>
        </div>

        @if ($trendingStems->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach ($trendingStems as $stem)
                    @php
                        $downloadUrl = $stem->mega_link && filter_var($stem->mega_link, FILTER_VALIDATE_URL)
                            ? $stem->mega_link
                            : route('webapp.stems.download', $stem->id);
                        $heroImage = $stem->featured_image ?: 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=1200&q=80';
                    @endphp
                    <article class="trend-card group overflow-hidden rounded-[30px]" data-like-card>
                        <div class="relative aspect-[16/10] overflow-hidden">
                            <img src="{{ $heroImage }}" alt="{{ $stem->title }}"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/10 to-transparent"></div>
                            <div class="absolute left-4 top-4 flex items-center gap-2">
                                <span class="rank-badge px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-[0.2em]">
                                    #{{ ($trendingStems->firstItem() ?? 1) + $loop->index }}
                                </span>
                                @if ($stem->music_key)
                                    <span class="px-3 py-1 rounded-full bg-black/55 soft-border text-[10px] font-black uppercase tracking-[0.2em] text-zinc-200">
                                        {{ $stem->music_key }}
                                    </span>
                                @endif
                            </div>
                            <div class="absolute right-4 bottom-4 flex gap-2">
                                <button type="button"
                                    class="copy-link-btn rounded-2xl bg-black/65 soft-border px-3 py-3 text-zinc-300 hover:text-white transition"
                                    data-url="{{ route('webapp.stems.show', $stem->id) }}"
                                    aria-label="Copy link">
                                    <i class="fa-solid fa-share-nodes text-xs"></i>
                                </button>
                                @if (Auth::check())
                                    <button type="button"
                                        data-stem-like-btn
                                        class="rounded-2xl bg-black/65 soft-border px-3 py-3 text-zinc-300 hover:text-red-400 transition {{ $stem->isLikedBy(auth()->id()) ? 'text-red-400' : '' }}"
                                        data-like-url="{{ route('webapp.stems.like', $stem->id) }}"
                                        data-stem-id="{{ $stem->id }}"
                                        data-liked="{{ $stem->isLikedBy(auth()->id()) ? 1 : 0 }}"
                                        aria-label="Like release">
                                        <i data-stem-like-icon class="fa-heart text-xs {{ $stem->isLikedBy(auth()->id()) ? 'fa-solid' : 'fa-regular' }}"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <h4 class="font-brand text-xl font-black uppercase tracking-tight text-white truncate">
                                        {{ $stem->title }}
                                    </h4>
                                    <p class="mt-1 text-sm text-zinc-500 truncate">
                                        {{ $stem->artist_name ?: 'Official NCS release' }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">Downloads</p>
                                    <p class="text-lg font-black text-white">{{ number_format($stem->download_count) }}</p>
                                </div>
                            </div>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="px-3 py-1 rounded-full bg-white/5 soft-border text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">
                                    {{ $stem->category->name ?? 'Uncategorized' }}
                                </span>
                                <span class="px-3 py-1 rounded-full bg-white/5 soft-border text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">
                                    <span data-like-count>{{ number_format($stem->like_count) }}</span> likes
                                </span>
                                <span class="px-3 py-1 rounded-full bg-white/5 soft-border text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">
                                    {{ number_format($stem->view_count) }} views
                                </span>
                            </div>

                            @if ($stem->description)
                                <p class="mt-4 text-sm leading-relaxed text-zinc-400">
                                    {{ \Illuminate\Support\Str::limit($stem->description, 120) }}
                                </p>
                            @endif

                            <div class="mt-5 grid grid-cols-2 gap-3">
                                <a href="{{ route('webapp.stems.show', $stem->id) }}"
                                    class="rounded-2xl px-4 py-3 text-center text-[10px] font-black uppercase tracking-[0.2em] bg-white text-black hover:bg-amber-500 transition">
                                    View details
                                </a>
                                <a href="{{ $downloadUrl }}" target="_blank"
                                    class="rounded-2xl px-4 py-3 text-center text-[10px] font-black uppercase tracking-[0.2em] btn-vault">
                                    Open release
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10 flex justify-center">
                {{ $trendingStems->links() }}
            </div>
        @else
            <div class="glass-panel rounded-[32px] p-10 text-center">
                <i class="fa-solid fa-wave-square text-4xl text-zinc-700"></i>
                <h4 class="mt-4 font-brand text-2xl font-black uppercase tracking-tight text-white">
                    No releases match your filters
                </h4>
                <p class="mt-2 text-sm text-zinc-500">
                    Try clearing the search or category filters to reveal the full chart.
                </p>
                <a href="{{ route('webapp.trending') }}" class="inline-flex mt-6 btn-vault px-6 py-3 rounded-2xl text-[10px] font-black tracking-[0.2em] uppercase">
                    Reset filters
                </a>
            </div>
        @endif
    </section>

    @if ($topCreators->isNotEmpty())
        <section class="mb-12">
            <div class="flex items-end justify-between gap-4 mb-5">
                <div>
                    <h3 class="font-brand text-2xl md:text-3xl font-black uppercase tracking-tight text-white">
                        Rising creators
                    </h3>
                    <p class="mt-2 text-sm text-zinc-500">
                        Artists driving the most activity in the current chart.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach ($topCreators as $creator)
                    @php
                        $creatorAvatar = $creator->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($creator->creator_name) . '&background=111111&color=fff&bold=true';
                    @endphp
                    <a href="{{ route('webapp.trending', array_merge(request()->except('page'), ['search' => $creator->creator_name])) }}"
                        class="trend-card rounded-[28px] p-5 flex items-center gap-4 hover:no-underline">
                        <img src="{{ $creatorAvatar }}" alt="{{ $creator->creator_name }}"
                            class="h-16 w-16 rounded-2xl object-cover border border-white/10">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <h4 class="font-brand text-lg font-black uppercase tracking-tight text-white truncate">
                                        {{ $creator->creator_name }}
                                    </h4>
                                    <p class="text-xs text-zinc-500 mt-1">Creator spotlight</p>
                                </div>
                                <span class="rank-badge px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-[0.2em]">
                                    {{ number_format($creator->downloads) }} plays
                                </span>
                            </div>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="px-3 py-1 rounded-full bg-white/5 soft-border text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">
                                    {{ number_format($creator->releases) }} releases
                                </span>
                                <span class="px-3 py-1 rounded-full bg-white/5 soft-border text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">
                                    {{ number_format($creator->likes) }} likes
                                </span>
                                <span class="px-3 py-1 rounded-full bg-white/5 soft-border text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">
                                    {{ number_format($creator->views) }} views
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</x-webapp-layout>

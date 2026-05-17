<x-webapp-layout
    title="Trending Music Charts | NCS Hindi"
    description="Discover the most played, liked, and downloaded royalty-free Hindi music on the official NCS Hindi chart. Live rank updates and creator highlights."
    keywords="trending hindi music, top ncs hindi, non copyright music chart, most downloaded hindi music, creator music chart"
>
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

            @media (max-width: 640px) {
                .trend-shell {
                    border-radius: 20px;
                }

                .trend-card:hover {
                    transform: none;
                }

                .trend-card {
                    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.22);
                }
            }
        </style>
    @endpush

    @php
        $sort = $filters['sort'] ?? 'downloads';
        $featuredImage = $featuredStem?->featured_image ?: 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=1600&q=80';
    @endphp

    <section class="trend-shell relative overflow-hidden rounded-[24px] md:rounded-[28px] mb-6 md:mb-8 soft-border">
        <div class="absolute inset-0 opacity-40 bg-[radial-gradient(circle_at_top_right,_rgba(245,158,11,0.16),_transparent_28%),radial-gradient(circle_at_bottom_left,_rgba(153,27,27,0.16),_transparent_26%)]"></div>
        <div class="relative grid lg:grid-cols-[1.02fr_0.98fr] gap-4 md:gap-5 p-3 sm:p-4 md:p-5 lg:p-6">
            <div class="space-y-4 md:space-y-5">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full glass-panel text-[9px] font-black uppercase tracking-[0.22em] text-amber-400">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    Trending now
                    <span class="hidden sm:inline text-zinc-500">/ dynamic music chart</span>
                </div>

                <div>
                    <h1 class="font-brand text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black uppercase tracking-tighter text-white leading-[0.94]">
                        Discover what music is
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-orange-400 to-red-500">
                            playing most
                        </span>
                    </h1>
                    <p class="mt-2 max-w-xl text-zinc-300 leading-relaxed text-[11px] sm:text-xs md:text-sm">
                        Live rankings, creator highlights, and instant access to the releases driving the most downloads,
                        likes, and views across NCS Hindi music.
                    </p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3">
                    <div class="glass-panel rounded-2xl p-2.5 sm:p-3 md:p-4">
                        <p class="text-[9px] uppercase tracking-[0.22em] text-zinc-500 font-black">Tracks</p>
                        <p class="mt-1 text-xl sm:text-2xl md:text-3xl font-black text-white">{{ number_format($trendingStats['tracks'] ?? 0) }}</p>
                    </div>
                    <div class="glass-panel rounded-2xl p-2.5 sm:p-3 md:p-4">
                        <p class="text-[9px] uppercase tracking-[0.22em] text-zinc-500 font-black">Downloads</p>
                        <p class="mt-1 text-xl sm:text-2xl md:text-3xl font-black text-white">{{ number_format($trendingStats['downloads'] ?? 0) }}</p>
                    </div>
                    <div class="glass-panel rounded-2xl p-2.5 sm:p-3 md:p-4">
                        <p class="text-[9px] uppercase tracking-[0.22em] text-zinc-500 font-black">Likes</p>
                        <p class="mt-1 text-xl sm:text-2xl md:text-3xl font-black text-white">{{ number_format($trendingStats['likes'] ?? 0) }}</p>
                    </div>
                    <div class="glass-panel rounded-2xl p-2.5 sm:p-3 md:p-4">
                        <p class="text-[9px] uppercase tracking-[0.22em] text-zinc-500 font-black">Views</p>
                        <p class="mt-1 text-xl sm:text-2xl md:text-3xl font-black text-white">{{ number_format($trendingStats['views'] ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-3 md:space-y-4">
                @if ($featuredStem)
                    <div class="relative overflow-hidden rounded-[26px] soft-border bg-black/40">
                        <div class="grid md:grid-cols-[1.08fr_0.92fr]">
                            <div class="relative order-2 md:order-1 min-h-[190px] sm:min-h-[220px] md:min-h-[280px] p-4 md:p-5 flex flex-col justify-end bg-gradient-to-br from-black via-[#0b0b0d] to-[#121214]">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="rank-badge px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-[0.2em]">
                                        Featured #1
                                    </span>
                                    @if ($featuredStem->category)
                                        <span class="px-2.5 py-1 rounded-full bg-white/5 soft-border text-[9px] font-black uppercase tracking-[0.2em] text-zinc-300">
                                            {{ $featuredStem->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <h2 class="font-brand text-lg sm:text-xl md:text-2xl font-black uppercase tracking-tighter text-white leading-[0.95] max-w-[14rem] sm:max-w-[18rem]">
                                    {{ $featuredStem->title }}
                                </h2>
                                <p class="mt-2 text-zinc-300 text-[11px] md:text-sm font-medium">
                                    {{ $featuredStem->artist_name ?: 'Unknown artist' }}
                                </p>
                                @if ($featuredStem->description)
                                    <p class="mt-2 text-[11px] md:text-sm leading-relaxed text-zinc-400 max-w-md">
                                        {{ \Illuminate\Support\Str::limit($featuredStem->description, 88) }}
                                    </p>
                                @endif

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <a href="{{ route('webapp.music.show', $featuredStem->slug) }}"
                                        data-notification-gate
                                        data-music-action="view"
                                        data-music-title="{{ $featuredStem->title }}"
                                        data-action-url="{{ route('webapp.music.show', $featuredStem->slug) }}"
                                        data-action-label="Continue to view"
                                        data-music-id="{{ $featuredStem->id }}"
                                        class="btn-vault px-8 py-2.5 rounded-2xl text-[9px] font-black tracking-[0.2em] uppercase">
                                        VIEW
                                    </a>
                                </div>
                            </div>
                            <div class="relative order-1 md:order-2 min-h-[190px] sm:min-h-[220px] md:min-h-[280px] overflow-hidden">
                                <img src="{{ $featuredImage }}" alt="{{ $featuredStem->title }}"
                                    class="absolute inset-0 h-full w-full object-cover opacity-85">
                                <div class="absolute inset-0 bg-gradient-to-l from-black/10 via-black/15 to-black/55"></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-2 space-y-3 md:space-y-4">
                <form action="{{ route('webapp.trending') }}" method="GET" class="glass-panel rounded-[18px] p-3 md:p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-[1.8fr_0.85fr_0.85fr_auto] gap-2.5 md:gap-3">
                        <div class="relative sm:col-span-2 xl:col-span-1">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-500 text-[11px]"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, artist, album, or tags"
                                class="w-full rounded-2xl bg-black/40 soft-border pl-10 pr-3.5 py-2.5 text-[13px] text-white outline-none focus:border-amber-500/50">
                        </div>
                        <select name="category" class="w-full rounded-2xl bg-black/40 soft-border px-3.5 py-2.5 text-[13px] text-zinc-300 outline-none focus:border-amber-500/50">
                            <option value="">All categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ number_format($category->stems_count) }})
                                </option>
                            @endforeach
                        </select>
                        <select name="sort" class="w-full rounded-2xl bg-black/40 soft-border px-3.5 py-2.5 text-[13px] text-zinc-300 outline-none focus:border-amber-500/50">
                            <option value="downloads" {{ $sort === 'downloads' ? 'selected' : '' }}>Top downloads</option>
                            <option value="likes" {{ $sort === 'likes' ? 'selected' : '' }}>Most liked</option>
                            <option value="views" {{ $sort === 'views' ? 'selected' : '' }}>Most viewed</option>
                            <option value="newest" {{ $sort === 'newest' || $sort === 'latest' ? 'selected' : '' }}>Newest releases</option>
                        </select>
                        <div class="flex items-stretch gap-2 sm:col-span-2 xl:col-span-1">
                            <button type="submit" class="btn-vault px-4 py-2.5 rounded-2xl text-[9px] font-black tracking-[0.2em] whitespace-nowrap flex-1 xl:flex-none">
                                Filter
                            </button>
                            <a href="{{ route('webapp.trending') }}" class="px-4 py-2.5 rounded-2xl bg-white/5 soft-border text-[9px] font-black tracking-[0.2em] uppercase text-zinc-300 hover:text-white hover:border-amber-500/40 transition flex-1 xl:flex-none text-center">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <div class="flex gap-2 overflow-x-auto pb-1 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    <a href="{{ route('webapp.trending', array_merge(request()->except('page'), ['sort' => 'downloads'])) }}"
                        class="shrink-0 px-3.5 py-2 rounded-full text-[9px] font-black tracking-[0.2em] uppercase transition {{ $sort === 'downloads' ? 'bg-amber-500 text-black' : 'bg-white/5 text-zinc-400 hover:text-white' }}">
                        Top Music
                    </a>
                    <a href="{{ route('webapp.trending', array_merge(request()->except('page'), ['sort' => 'likes'])) }}"
                        class="shrink-0 px-3.5 py-2 rounded-full text-[9px] font-black tracking-[0.2em] uppercase transition {{ $sort === 'likes' ? 'bg-amber-500 text-black' : 'bg-white/5 text-zinc-400 hover:text-white' }}">
                        Most liked
                    </a>
                    <a href="{{ route('webapp.trending', array_merge(request()->except('page'), ['sort' => 'views'])) }}"
                        class="shrink-0 px-3.5 py-2 rounded-full text-[9px] font-black tracking-[0.2em] uppercase transition {{ $sort === 'views' ? 'bg-amber-500 text-black' : 'bg-white/5 text-zinc-400 hover:text-white' }}">
                        Most viewed
                    </a>
                    <a href="{{ route('webapp.trending', array_merge(request()->except('page'), ['sort' => 'newest'])) }}"
                        class="shrink-0 px-3.5 py-2 rounded-full text-[9px] font-black tracking-[0.2em] uppercase transition {{ $sort === 'newest' || $sort === 'latest' ? 'bg-amber-500 text-black' : 'bg-white/5 text-zinc-400 hover:text-white' }}">
                        Newest
                    </a>
                </div>

            </div>
        </div>
    </section>

    @if ($categories->isNotEmpty())
        <section class="mb-8">
            <div class="flex items-center justify-between mb-2.5 md:mb-3">
                <h3 class="font-brand text-base sm:text-lg md:text-2xl font-black uppercase tracking-tight text-white">Browse categories</h3>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500">Quick filters</span>
            </div>

            <div class="flex gap-2 overflow-x-auto pb-1 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <a href="{{ route('webapp.trending', array_merge(request()->except(['page', 'category']), [])) }}"
                    class="shrink-0 px-3 py-1.5 rounded-full text-[8px] font-black uppercase tracking-[0.2em] transition {{ !request('category') ? 'bg-white text-black' : 'bg-white/5 text-zinc-400 hover:text-white' }}">
                    All
                </a>
                @foreach ($categories->take(8) as $category)
                    <a href="{{ route('webapp.trending', array_merge(request()->except('page'), ['category' => $category->slug])) }}"
                        class="shrink-0 px-3 py-1.5 rounded-full text-[8px] font-black uppercase tracking-[0.2em] transition {{ request('category') == $category->slug ? 'bg-amber-500 text-black' : 'bg-white/5 text-zinc-400 hover:text-white' }}">
                        {{ $category->name }}
                        <span class="ml-1.5 text-[8px] opacity-70">{{ number_format($category->stems_count) }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mb-8">
        <div class="flex items-end justify-between gap-3 mb-3">
            <div>
                <h3 class="font-brand text-lg sm:text-xl md:text-3xl font-black uppercase tracking-tight text-white">
                    Trending music
                </h3>
                <p class="mt-1 text-[11px] md:text-sm text-zinc-500">
                    Ranked by {{ $sort === 'likes' ? 'likes' : ($sort === 'views' ? 'views' : ($sort === 'newest' || $sort === 'latest' ? 'freshness' : 'downloads')) }}.
                </p>
            </div>
            <p class="hidden sm:block text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500">
                Showing {{ number_format($trendingStems->count()) }} of {{ number_format($trendingStems->total()) }}
            </p>
        </div>

        @if ($trendingStems->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                @foreach ($trendingStems as $music)
                    @php
                        $downloadUrl = $music->mega_link && filter_var($music->mega_link, FILTER_VALIDATE_URL)
                            ? $music->mega_link
                            : route('webapp.music.download', $music->id);
                        $heroImage = $music->featured_image ?: 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=1200&q=80';
                        $stemLanguages = collect(explode(',', (string) $music->language))
                            ->map(fn ($language) => trim($language))
                            ->filter()
                            ->values();
                    @endphp
                    <article class="trend-card group overflow-hidden rounded-[20px]" data-like-card>
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ $heroImage }}" alt="{{ $music->title }}"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/10 to-transparent"></div>
                            <div class="absolute left-2.5 top-2.5 flex items-center gap-1.5">
                                <span class="rank-badge px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-[0.2em]">
                                    #{{ ($trendingStems->firstItem() ?? 1) + $loop->index }}
                                </span>
                                @if ($music->music_key)
                                    <span class="px-2.5 py-1 rounded-full bg-black/55 soft-border text-[9px] font-black uppercase tracking-[0.2em] text-zinc-200">
                                        {{ $music->music_key }}
                                    </span>
                                @endif
                            </div>
                            <div class="absolute right-2.5 bottom-2.5 flex gap-2">
                                <button type="button"
                                    class="rounded-2xl bg-black/65 soft-border px-2.5 py-2.5 text-zinc-300 hover:text-white transition"
                                    data-music-share-btn
                                    data-share-title="{{ $music->title }}"
                                    data-share-url="{{ route('webapp.music.show', $music->slug) }}"
                                    aria-label="Share release">
                                    <i class="fa-solid fa-share-nodes text-[11px]"></i>
                                </button>
                                @if (Auth::check())
                                    <button type="button"
                                        data-music-like-btn
                                        class="rounded-2xl bg-black/65 soft-border px-2.5 py-2.5 text-zinc-300 hover:text-red-400 transition {{ $music->isLikedBy(auth()->id()) ? 'text-red-400' : '' }}"
                                        data-like-url="{{ route('webapp.music.like', $music->id) }}"
                                        data-music-id="{{ $music->id }}"
                                        data-liked="{{ $music->isLikedBy(auth()->id()) ? 1 : 0 }}"
                                        aria-label="Like release">
                                        <i data-music-like-icon class="fa-heart text-[11px] {{ $music->isLikedBy(auth()->id()) ? 'fa-solid' : 'fa-regular' }}"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="p-3.5 md:p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h4 class="font-brand text-base md:text-lg font-black uppercase tracking-tight text-white truncate">
                                        {{ $music->title }}
                                    </h4>
                                    <p class="mt-1 text-[11px] md:text-sm text-zinc-500 truncate">
                                        {{ $music->artist_name ?: 'Official NCS release' }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-[8px] font-black uppercase tracking-[0.2em] text-zinc-500">Downloads</p>
                                    <p class="text-sm md:text-base font-black text-white">{{ number_format($music->download_count) }}</p>
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="px-2.5 py-1 rounded-full bg-white/5 soft-border text-[8px] font-black uppercase tracking-[0.2em] text-zinc-400">
                                    {{ $music->category->name ?? 'Uncategorized' }}
                                </span>
                                @if ($stemLanguages->isNotEmpty())
                                    <span class="px-2.5 py-1 rounded-full bg-white/5 soft-border text-[8px] font-black uppercase tracking-[0.2em] text-zinc-400">
                                        {{ $stemLanguages->first() }}
                                        @if ($stemLanguages->count() > 1)
                                            +{{ $stemLanguages->count() - 1 }}
                                        @endif
                                    </span>
                                @endif
                                <span class="px-2.5 py-1 rounded-full bg-white/5 soft-border text-[8px] font-black uppercase tracking-[0.2em] text-zinc-400">
                                    <span data-like-count>{{ number_format($music->like_count) }}</span> likes
                                </span>
                                <span class="px-2.5 py-1 rounded-full bg-white/5 soft-border text-[8px] font-black uppercase tracking-[0.2em] text-zinc-400">
                                    {{ number_format($music->view_count) }} views
                                </span>
                            </div>

                            @if ($music->description)
                                <p class="mt-2.5 text-[11px] md:text-sm leading-relaxed text-zinc-400">
                                    {{ \Illuminate\Support\Str::limit($music->description, 80) }}
                                </p>
                            @endif

                            <div class="mt-3.5">
                                <a href="{{ route('webapp.music.show', $music->slug) }}"
                                    data-notification-gate
                                    data-music-action="view"
                                    data-music-title="{{ $music->title }}"
                                    data-action-url="{{ route('webapp.music.show', $music->slug) }}"
                                    data-action-label="Continue to view"
                                    data-music-id="{{ $music->id }}"
                                    class="block w-full rounded-2xl px-3 py-3 text-center text-[9px] font-black uppercase tracking-[0.2em] bg-white text-black hover:bg-amber-500 transition">
                                    VIEW
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $trendingStems->links('layouts.partials.webapp.pagination') }}
            </div>
        @else
            <div class="glass-panel rounded-[24px] p-6 md:p-8 text-center">
                <i class="fa-solid fa-wave-square text-4xl text-zinc-700"></i>
                <h4 class="mt-3 font-brand text-lg md:text-2xl font-black uppercase tracking-tight text-white">
                    No releases match your filters
                </h4>
                <p class="mt-2 text-[11px] md:text-sm text-zinc-500">
                    Try clearing the search or category filters to reveal the full chart.
                </p>
                <a href="{{ route('webapp.trending') }}" class="inline-flex mt-4 btn-vault px-4 py-2.5 rounded-2xl text-[8px] font-black tracking-[0.2em] uppercase">
                    Reset filters
                </a>
            </div>
        @endif
    </section>

</x-webapp-layout>








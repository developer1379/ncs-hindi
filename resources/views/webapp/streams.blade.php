<x-webapp-layout>
    {{-- 1. Library Search & Stats --}}
    <section class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-12 px-2">
        <div class="flex-1">
                <h1 class="font-brand text-4xl font-black text-white uppercase tracking-tighter">
                MUSIC <span class="text-amber-500 italic">Library</span>
            </h1>
            <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-[0.2em] mt-1">
                Official Studio-Grade Hindi NCS Assets
            </p>
        </div>

        <div class="flex items-center gap-4 bg-zinc-900/40 p-3 rounded-[24px] border border-zinc-800 backdrop-blur-md">
            <div class="px-6 py-1 border-r border-zinc-800 text-center">
                <p class="text-2xl font-black text-white leading-none">{{ $stems->total() }}</p>
                <p class="text-[8px] text-zinc-500 uppercase font-black tracking-tighter mt-1">Tracks</p>
            </div>
            <div class="px-6 py-1 text-center">
                <p class="text-2xl font-black text-amber-500 leading-none">320</p>
                <p class="text-[8px] text-zinc-500 uppercase font-black tracking-tighter mt-1">KBPS</p>
            </div>
        </div>
    </section>

    {{-- 2. Technical Filtering System --}}
    <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-12">
        <div class="md:col-span-6 relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-amber-500 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search by title, artist, or album..."
                class="w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl py-4 pl-12 pr-4 text-xs text-white focus:border-amber-500 outline-none transition-all">
        </div>

        <div class="md:col-span-3">
            <select name="category" onchange="this.form.submit()"
                class="w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl px-4 py-4 text-xs text-zinc-400 focus:border-amber-500 outline-none cursor-pointer appearance-none">
                <option value="">All Categories</option>
                @foreach (\App\Models\Category::where('is_active', 1)->get() as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-3">
            <select name="sort" onchange="this.form.submit()"
                class="w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl px-4 py-4 text-xs text-zinc-400 focus:border-amber-500 outline-none cursor-pointer appearance-none">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest Releases</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
            </select>
        </div>
    </form>

    {{-- 3. Stems Grid --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($stems as $stem)
            @php
                $stemLanguages = collect(explode(',', (string) $stem->language))
                    ->map(fn ($language) => trim($language))
                    ->filter()
                    ->values();
            @endphp
            <div class="group bg-zinc-900/30 border border-zinc-800/60 rounded-[32px] overflow-hidden hover:border-amber-500/40 transition-all duration-500" data-like-card>

                {{-- Artwork --}}
                <div class="relative aspect-square m-3 overflow-hidden rounded-[24px] bg-zinc-800">
                    @if ($stem->featured_image)
                        <img src="{{ $stem->featured_image }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            alt="{{ $stem->title }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fa-solid fa-music text-4xl text-zinc-700"></i>
                        </div>
                    @endif

                    {{-- Status Badges --}}
                    <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                        @if($stem->bpm)
                            <span class="px-2 py-1 bg-black/70 backdrop-blur-md rounded-lg text-[8px] font-black text-white border border-white/10">
                                {{ $stem->bpm }} BPM
                            </span>
                        @endif
                        @if($stem->music_key)
                            <span class="px-2 py-1 bg-amber-500/90 backdrop-blur-md rounded-lg text-[8px] font-black text-black border border-amber-400/20">
                                KEY: {{ $stem->music_key }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-5 pt-1">
                    <div class="mb-4">
                        <div class="flex justify-between items-start gap-2">
                            <h4 class="font-brand text-lg font-bold text-white uppercase tracking-tighter truncate">
                                {{ $stem->title }}
                            </h4>
                        </div>
                        <p class="text-xs text-zinc-500 font-bold truncate">
                            {{ $stem->artist_name ?: 'Official NCS Asset' }}
                        </p>
                    </div>

                    @if ($stem->description || $stemLanguages->isNotEmpty())
                        <div class="mb-4 space-y-2">
                            @if ($stem->description)
                                <p class="text-xs leading-relaxed text-zinc-500">
                                    {{ \Illuminate\Support\Str::limit($stem->description, 95) }}
                                </p>
                            @endif
                            @if ($stemLanguages->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($stemLanguages->take(3) as $language)
                                        <span class="px-2 py-1 rounded-full bg-white/5 border border-white/5 text-[8px] font-black uppercase tracking-[0.2em] text-zinc-400">
                                            {{ $language }}
                                        </span>
                                    @endforeach
                                    @if ($stemLanguages->count() > 3)
                                        <span class="px-2 py-1 rounded-full bg-white/5 border border-white/5 text-[8px] font-black uppercase tracking-[0.2em] text-zinc-500">
                                            +{{ $stemLanguages->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Stats --}}
                    <div class="flex items-center justify-between py-3 border-y border-zinc-800/50">
                        <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1">
                            <i class="fa-solid fa-heart text-[10px] text-zinc-600"></i>
                            <span data-like-count class="text-[10px] font-black text-zinc-400">{{ number_format($stem->like_count) }}</span>
                        </div>
                            <div class="flex items-center gap-1">
                                <i class="fa-solid fa-download text-[10px] text-zinc-600"></i>
                                <span class="text-[10px] font-black text-zinc-400">{{ number_format($stem->download_count) }}</span>
                            </div>
                        </div>
                        <span class="text-[9px] font-bold text-zinc-600 uppercase">
                            {{ $stem->file_size ?: 'Studio' }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="grid grid-cols-2 gap-3 mt-5">
                        <a href="{{ route('webapp.stems.show', $stem->slug) }}"
                            data-notification-gate
                            data-music-action="view"
                            data-music-title="{{ $stem->title }}"
                            data-action-url="{{ route('webapp.stems.show', $stem->slug) }}"
                            data-action-label="Continue to view"
                            class="py-3 bg-zinc-800/50 border border-zinc-700 rounded-xl text-zinc-400 text-[10px] font-black uppercase text-center hover:bg-zinc-800 hover:text-white transition-all">
                            View Details
                        </a>

                        @if($stem->mega_link)
                            <a href="{{ $stem->mega_link }}" target="_blank"
                                data-notification-gate
                                data-music-action="download"
                                data-music-title="{{ $stem->title }}"
                                data-action-url="{{ $stem->mega_link }}"
                                data-action-label="Continue to download"
                                class="py-3 bg-white text-black rounded-xl text-[10px] font-black uppercase text-center hover:bg-amber-500 hover:text-white transition-all">
                                <i class="fa-solid fa-external-link mr-1"></i> Mega Link
                            </a>
                        @else
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('webapp.stems.download', $stem->id) }}"
                                    data-notification-gate
                                    data-music-action="download"
                                    data-music-title="{{ $stem->title }}"
                                    data-action-url="{{ route('webapp.stems.download', $stem->id) }}"
                                    data-action-label="Continue to download"
                                    class="py-3 bg-amber-500 text-black rounded-xl text-[10px] font-black uppercase text-center hover:bg-white transition-all">
                                    <i class="fa-solid fa-download mr-1"></i> Download
                                </a>
                                <button type="button"
                                    data-stem-like-btn
                                    data-like-url="{{ route('webapp.stems.like', $stem->id) }}"
                                    data-liked="{{ auth()->check() && $stem->isLikedBy(auth()->id()) ? 1 : 0 }}"
                                    aria-pressed="{{ auth()->check() && $stem->isLikedBy(auth()->id()) ? 'true' : 'false' }}"
                                    class="py-3 bg-zinc-800 border border-zinc-700 rounded-xl text-zinc-300 text-[10px] font-black uppercase text-center hover:bg-zinc-700 transition-all {{ auth()->check() && $stem->isLikedBy(auth()->id()) ? 'text-red-400' : '' }}">
                                    <i data-stem-like-icon class="fa-heart mr-1 {{ auth()->check() && $stem->isLikedBy(auth()->id()) ? 'fa-solid' : 'fa-regular' }}"></i>
                                    Like
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center border border-dashed border-zinc-800 rounded-[40px]">
                <i class="fa-solid fa-box-open text-4xl text-zinc-800 mb-4"></i>
                <h5 class="text-zinc-500 font-bold uppercase tracking-widest text-xs">No assets found</h5>
            </div>
        @endforelse
    </section>

    <div class="mt-12">
        {{ $stems->links() }}
    </div>
</x-webapp-layout>

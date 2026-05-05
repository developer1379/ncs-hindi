<x-webapp-layout>
    @php
        $stemLanguages = collect(explode(',', (string) $stem->language))
            ->map(fn($language) => trim($language))
            ->filter()
            ->values();
    @endphp
    {{-- 1. Hero Player & Dynamic Background --}}
    <section class="relative mb-10 rounded-[40px] overflow-hidden border border-zinc-800 bg-zinc-900/40 group">
        <div
            class="absolute inset-0 bg-gradient-to-br from-amber-600/20 via-transparent to-black opacity-60 transition-opacity group-hover:opacity-80">
        </div>

        <div class="relative p-6 md:p-10 lg:p-16 flex flex-col md:flex-row items-center md:items-end gap-8 lg:gap-14">
            {{-- Artwork with Hover Effect --}}
            <div class="relative w-56 h-56 lg:w-72 lg:h-72 flex-shrink-0">
                <div class="absolute -inset-4 bg-amber-500/20 blur-3xl rounded-full opacity-50"></div>
                @if ($stem->featured_image)
                    <img src="{{ $stem->featured_image }}"
                        class="relative w-full h-full object-cover rounded-3xl border border-zinc-700 shadow-2xl transition-transform duration-500 hover:scale-105"
                        alt="{{ $stem->title }}">
                @else
                    <div
                        class="relative w-full h-full bg-zinc-800 rounded-3xl flex items-center justify-center border border-zinc-700">
                        <i class="fa-solid fa-compact-disc text-6xl text-zinc-600 animate-spin-slow"></i>
                    </div>
                @endif
            </div>

            {{-- Metadata Content --}}
            <div class="flex-1 text-center md:text-left z-10">
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 mb-4">
                    <span
                        class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-500 text-[10px] font-black uppercase tracking-widest border border-amber-500/20">
                        {{ $stem->category->name ?? 'Official Release' }}
                    </span>
                    @if ($stemLanguages->isNotEmpty())
                        <span
                            class="px-3 py-1 rounded-full bg-zinc-800 text-zinc-400 text-[10px] font-bold uppercase border border-zinc-700">
                            <i class="fa-solid fa-earth-asia mr-1"></i> {{ $stemLanguages->first() }}
                            @if ($stemLanguages->count() > 1)
                                +{{ $stemLanguages->count() - 1 }}
                            @endif
                        </span>
                    @endif
                </div>

                <h1
                    class="text-4xl lg:text-6xl font-brand font-black text-white uppercase tracking-tighter mb-3 leading-none">
                    {{ $stem->title }}
                </h1>

                <p class="text-xl lg:text-2xl text-zinc-400 font-bold mb-8">
                    {{ $stem->artist_name ?: 'NCS Artist' }}
                    @if ($stem->album_movie_name)
                        <span class="text-zinc-600 mx-2">•</span>
                        <span class="text-amber-500/80">{{ $stem->album_movie_name }}</span>
                    @endif
                </p>

                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                    {{-- Primary Action: Download --}}
                    @if ($stem->mega_link)
                        <a href="{{ $stem->mega_link }}" target="_blank" data-notification-gate
                            data-music-action="download" data-music-title="{{ $stem->title }}"
                            data-action-url="{{ $stem->mega_link }}" data-action-label="Continue to download"
                            class="w-full sm:w-auto bg-white text-black px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-amber-500 hover:text-white transition-all duration-300 shadow-xl shadow-white/5">
                            <i class="fa-solid fa-cloud-arrow-down text-lg"></i> NCS Version
                        </a>
                    @else
                        <a href="{{ route('webapp.stems.download', $stem->id) }}" target="_blank"
                            data-notification-gate data-music-action="download" data-music-title="{{ $stem->title }}"
                            data-action-url="{{ route('webapp.stems.download', $stem->id) }}"
                            data-action-label="Continue to download"
                            class="w-full sm:w-auto btn-vault px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-3 hover:scale-105 transition-all shadow-lg shadow-amber-600/20">
                            <i class="fa-solid fa-download text-lg"></i> NCS Version
                        </a>
                    @endif

                    {{-- Secondary Actions --}}
                    <div class="flex gap-3 w-full sm:w-auto justify-center" data-like-card>
                        <button type="button" data-stem-like-btn
                            data-like-url="{{ route('webapp.stems.like', $stem->id) }}"
                            data-liked="{{ auth()->check() && $stem->isLikedBy(auth()->id()) ? 1 : 0 }}"
                            aria-pressed="{{ auth()->check() && $stem->isLikedBy(auth()->id()) ? 'true' : 'false' }}"
                            class="flex-1 sm:flex-none p-4 bg-zinc-800/80 backdrop-blur-md border border-zinc-700 rounded-2xl text-zinc-300 hover:text-red-500 hover:border-red-500/50 transition-all group {{ auth()->check() && $stem->isLikedBy(auth()->id()) ? 'text-red-400' : '' }}">
                            <i data-stem-like-icon
                                class="fa-heart group-hover:scale-125 transition {{ auth()->check() && $stem->isLikedBy(auth()->id()) ? 'fa-solid' : 'fa-regular' }}"></i>
                        </button>
                        <div
                            class="flex min-w-[92px] items-center justify-center rounded-2xl bg-zinc-800/60 border border-zinc-700 px-4 py-3 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">
                            <span data-like-count>{{ number_format($stem->like_count) }}</span>
                            <span class="ml-1">likes</span>
                        </div>
                        <button type="button" data-stem-share-btn data-share-title="{{ $stem->title }}"
                            data-share-url="{{ url()->current() }}"
                            class="flex-1 sm:flex-none p-4 bg-zinc-800/80 backdrop-blur-md border border-zinc-700 rounded-2xl text-zinc-300 hover:text-amber-500 hover:border-amber-500/50 transition-all group">
                            <i class="fa-solid fa-share-nodes group-hover:rotate-12 transition"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Information Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">

        {{-- Technical Specifications Card --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-zinc-900/40 border border-zinc-800 rounded-[32px] p-6 md:p-10 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-8">
                    <h3
                        class="text-white font-brand font-bold text-xl uppercase tracking-tight flex items-center gap-3">
                        <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
                        Music Info
                    </h3>
                </div>

                @if ($stem->description)
                    <div class="mt-10 pt-8 border-t border-zinc-800/50">
                        <h4
                            class="text-zinc-500 text-[10px] font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-align-left text-amber-500"></i> Description
                        </h4>
                        <p class="text-zinc-400 text-base leading-relaxed font-medium">
                            {{ $stem->description }}
                        </p>
                    </div>
                @endif

                @if ($stemLanguages->isNotEmpty())
                    <div class="mt-6 pt-6 border-t border-zinc-800/50">
                        <h4
                            class="text-zinc-500 text-[10px] font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-language text-amber-500"></i> Languages
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($stemLanguages as $language)
                                <span
                                    class="px-3 py-2 rounded-xl bg-white/5 border border-white/5 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">
                                    {{ $language }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- New: Licensing & Usage Card --}}
            <div
                class="bg-gradient-to-br from-zinc-900 to-black border border-zinc-800 rounded-[32px] p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <i class="fa-solid fa-shield-halved text-7xl text-white"></i>
                </div>
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    <div
                        class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center border border-amber-500/20 shrink-0">
                        <i class="fa-solid fa-certificate text-amber-500 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white text-xl font-bold uppercase tracking-tight text-center md:text-left">
                            Creator Friendly License</h3>
                        <div class="text-zinc-500 text-sm mt-3 leading-relaxed text-center md:text-left space-y-3">
                            <p>
                                {!! nl2br(
                                    e(
                                        $stem->license_text ?:
                                        'This music is safe for use in YouTube, Twitch, Shorts, Reels, and other social media content. You may use this track in monetized videos as long as you give clear credit in the description. Include the track title and artist name, and do not claim the music as your own original composition.',
                                    ),
                                ) !!}
                            </p>
                        </div>
                        <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-4">
                            <span class="flex items-center gap-2 text-[10px] font-bold text-green-500 uppercase">
                                <i class="fa-solid fa-check-circle"></i> Monetization Active
                            </span>
                            <span class="flex items-center gap-2 text-[10px] font-bold text-green-500 uppercase">
                                <i class="fa-solid fa-check-circle"></i> No Copyright Strike
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Stats & Discovery --}}
        <div class="space-y-6">
            {{-- Community Performance Card --}}
            <div class="bg-zinc-900/40 border border-zinc-800 rounded-[32px] p-8 text-center backdrop-blur-sm">
                <h4 class="text-[10px] text-zinc-600 font-black uppercase tracking-widest mb-6">Music Stats</h4>
                <div class="grid grid-cols-3 gap-4 relative">
                    <div class="absolute inset-y-4 left-1/3 w-[1px] bg-zinc-800"></div>
                    <div class="absolute inset-y-4 left-2/3 w-[1px] bg-zinc-800"></div>
                    <div>
                        <p class="text-3xl font-black text-white">{{ number_format($stem->download_count) }}</p>
                        <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-tighter mt-2">Downloads</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-white">{{ number_format($stem->view_count) }}</p>
                        <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-tighter mt-2">Music Views</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-white">{{ number_format($stem->like_count) }}</p>
                        <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-tighter mt-2">Likes</p>
                    </div>
                </div>
            </div>

            {{-- Interactive Keywords --}}
            @if ($stem->tags_keywords)
                <div class="bg-zinc-900/40 border border-zinc-800 rounded-[32px] p-8 backdrop-blur-sm">
                    <h4
                        class="text-[10px] text-zinc-500 font-black uppercase tracking-widest mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-tags text-amber-500"></i> Metadata Tags
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach (explode(',', $stem->tags_keywords) as $tag)
                            <a href="#"
                                class="px-4 py-2 bg-black rounded-xl text-[11px] font-bold text-zinc-400 border border-zinc-800 hover:border-amber-500/50 hover:text-white transition-all">
                                #{{ trim($tag) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Share Link Card --}}
            <div class="p-1 rounded-[32px] bg-gradient-to-r from-amber-500/20 to-transparent">
                <div class="bg-zinc-900 border border-zinc-800 rounded-[30px] p-6">
                    <p class="text-white font-bold text-sm mb-4">Share this Release</p>
                    <button type="button" data-stem-share-btn data-share-title="{{ $stem->title }}"
                        data-share-url="{{ url()->current() }}"
                        class="w-full bg-black/50 border border-zinc-800 rounded-xl p-3 text-left text-zinc-500 text-xs hover:border-amber-500/40 hover:text-zinc-300 transition-colors">
                        Open share options
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="shareMusicModal"
        class="fixed inset-0 z-[200] hidden items-center justify-center px-4 py-6 bg-black/70 backdrop-blur-sm">
        <div class="absolute inset-0" data-share-dismiss></div>
        <div
            class="relative w-full max-w-2xl rounded-[28px] border border-zinc-800 bg-[#101014] text-white shadow-2xl shadow-black/50 overflow-hidden">
            <div class="flex items-start justify-between gap-4 border-b border-zinc-800 px-4 md:px-5 py-4">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.25em] text-amber-400 font-black">Share music</p>
                    <h5 class="font-brand text-2xl font-black uppercase tracking-tight">Spread the track</h5>
                </div>
                <button type="button" class="text-zinc-400 hover:text-white transition" data-share-dismiss
                    aria-label="Close">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="p-4 md:p-5">
                <div class="rounded-[24px] bg-black/40 border border-zinc-800 p-4 md:p-5 mb-4">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-zinc-500 font-black mb-2">Release</p>
                    <h6 id="shareMusicTitle"
                        class="text-lg md:text-xl font-black text-white uppercase tracking-tight"></h6>
                    <p id="shareMusicUrl" class="mt-2 text-xs text-zinc-500 break-all"></p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="#" target="_blank" rel="noopener" data-share-channel="whatsapp"
                        class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-green-500/50 hover:bg-green-500/10 transition">
                        <i class="fa-brands fa-whatsapp text-lg text-green-500"></i>
                        <span
                            class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">WhatsApp</span>
                    </a>
                    <a href="#" target="_blank" rel="noopener" data-share-channel="x"
                        class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-white/30 hover:bg-white/5 transition">
                        <i class="fa-brands fa-x-twitter text-lg text-white"></i>
                        <span
                            class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">X</span>
                    </a>
                    <a href="#" target="_blank" rel="noopener" data-share-channel="facebook"
                        class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-blue-500/50 hover:bg-blue-500/10 transition">
                        <i class="fa-brands fa-facebook text-lg text-blue-500"></i>
                        <span
                            class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">Facebook</span>
                    </a>
                    <a href="#" target="_blank" rel="noopener" data-share-channel="telegram"
                        class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-sky-500/50 hover:bg-sky-500/10 transition">
                        <i class="fa-brands fa-telegram text-lg text-sky-400"></i>
                        <span
                            class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">Telegram</span>
                    </a>
                    <a href="#" target="_blank" rel="noopener" data-share-channel="linkedin"
                        class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-blue-400/50 hover:bg-blue-400/10 transition">
                        <i class="fa-brands fa-linkedin text-lg text-blue-400"></i>
                        <span
                            class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">LinkedIn</span>
                    </a>
                    <a href="#" target="_blank" rel="noopener" data-share-channel="reddit"
                        class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-orange-500/50 hover:bg-orange-500/10 transition">
                        <i class="fa-brands fa-reddit text-lg text-orange-500"></i>
                        <span
                            class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">Reddit</span>
                    </a>
                    <a href="#" target="_blank" rel="noopener" data-share-channel="email"
                        class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-amber-500/50 hover:bg-amber-500/10 transition">
                        <i class="fa-solid fa-envelope text-lg text-amber-400"></i>
                        <span
                            class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">Email</span>
                    </a>
                    <button type="button" data-share-copy
                        class="share-target rounded-2xl border border-zinc-800 bg-black/40 px-4 py-4 text-center hover:border-amber-500/50 hover:bg-amber-500/10 transition">
                        <i class="fa-solid fa-link text-lg text-amber-400"></i>
                        <span class="mt-2 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-300">Copy
                            link</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-webapp-layout>

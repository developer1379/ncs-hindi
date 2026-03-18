<x-webapp-layout>
    {{-- 1. Hero Player & Dynamic Background --}}
    <section class="relative mb-10 rounded-[40px] overflow-hidden border border-zinc-800 bg-zinc-900/40 group">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-600/20 via-transparent to-black opacity-60 transition-opacity group-hover:opacity-80"></div>

        <div class="relative p-6 md:p-10 lg:p-16 flex flex-col md:flex-row items-center md:items-end gap-8 lg:gap-14">
            {{-- Artwork with Hover Effect --}}
            <div class="relative w-56 h-56 lg:w-72 lg:h-72 flex-shrink-0">
                <div class="absolute -inset-4 bg-amber-500/20 blur-3xl rounded-full opacity-50"></div>
                @if ($stem->featured_image)
                    <img src="{{ $stem->featured_image }}"
                        class="relative w-full h-full object-cover rounded-3xl border border-zinc-700 shadow-2xl transition-transform duration-500 hover:scale-105"
                        alt="{{ $stem->title }}">
                @else
                    <div class="relative w-full h-full bg-zinc-800 rounded-3xl flex items-center justify-center border border-zinc-700">
                        <i class="fa-solid fa-compact-disc text-6xl text-zinc-600 animate-spin-slow"></i>
                    </div>
                @endif
            </div>

            {{-- Metadata Content --}}
            <div class="flex-1 text-center md:text-left z-10">
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 mb-4">
                    <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-500 text-[10px] font-black uppercase tracking-widest border border-amber-500/20">
                        {{ $stem->category->name ?? 'Official Release' }}
                    </span>
                    @if($stem->language)
                        <span class="px-3 py-1 rounded-full bg-zinc-800 text-zinc-400 text-[10px] font-bold uppercase border border-zinc-700">
                            <i class="fa-solid fa-earth-asia mr-1"></i> {{ $stem->language }}
                        </span>
                    @endif
                </div>

                <h1 class="text-4xl lg:text-6xl font-brand font-black text-white uppercase tracking-tighter mb-3 leading-none">
                    {{ $stem->title }}
                </h1>

                <p class="text-xl lg:text-2xl text-zinc-400 font-bold mb-8">
                    {{ $stem->artist_name ?: 'NCS Artist' }}
                    @if($stem->album_movie_name)
                        <span class="text-zinc-600 mx-2">•</span>
                        <span class="text-amber-500/80">{{ $stem->album_movie_name }}</span>
                    @endif
                </p>

                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                    {{-- Primary Action: Download --}}
                    @if($stem->mega_link)
                        <a href="{{ $stem->mega_link }}" target="_blank"
                            class="w-full sm:w-auto bg-white text-black px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-amber-500 hover:text-white transition-all duration-300 shadow-xl shadow-white/5">
                            <i class="fa-solid fa-cloud-arrow-down text-lg"></i> Download via Mega
                        </a>
                    @else
                        <a href="{{ route('webapp.stems.download', $stem->id) }}"
                            class="w-full sm:w-auto btn-vault px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-3 hover:scale-105 transition-all shadow-lg shadow-amber-600/20">
                            <i class="fa-solid fa-download text-lg"></i> Get Stems ({{ $stem->file_size }})
                        </a>
                    @endif

                    {{-- Secondary Actions --}}
                    <div class="flex gap-3 w-full sm:w-auto justify-center">
                        <button onclick="toggleLike('{{ $stem->id }}')"
                            class="flex-1 sm:flex-none p-4 bg-zinc-800/80 backdrop-blur-md border border-zinc-700 rounded-2xl text-zinc-300 hover:text-red-500 hover:border-red-500/50 transition-all group">
                            <i class="fa-solid fa-heart group-hover:scale-125 transition"></i>
                        </button>
                        <button onclick="shareMusic()"
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
                    <h3 class="text-white font-brand font-bold text-xl uppercase tracking-tight flex items-center gap-3">
                        <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
                        Technical Specs
                    </h3>
                    <span class="text-zinc-600 text-[10px] font-black uppercase tracking-tighter">Verified Studio Asset</span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    <div class="group p-5 bg-black/40 rounded-3xl border border-zinc-800 hover:border-amber-500/30 transition-colors">
                        <p class="text-[10px] text-zinc-500 font-black uppercase tracking-widest mb-2 group-hover:text-amber-500">BPM</p>
                        <p class="text-2xl font-black text-white">{{ $stem->bpm ?? 'N/A' }}</p>
                    </div>
                    <div class="group p-5 bg-black/40 rounded-3xl border border-zinc-800 hover:border-amber-500/30 transition-colors">
                        <p class="text-[10px] text-zinc-500 font-black uppercase tracking-widest mb-2 group-hover:text-amber-500">Scale/Key</p>
                        <p class="text-2xl font-black text-white">{{ $stem->music_key ?? 'N/A' }}</p>
                    </div>
                    <div class="group p-5 bg-black/40 rounded-3xl border border-zinc-800 hover:border-amber-500/30 transition-colors">
                        <p class="text-[10px] text-zinc-500 font-black uppercase tracking-widest mb-2 group-hover:text-amber-500">File Size</p>
                        <p class="text-2xl font-black text-white text-nowrap">{{ $stem->file_size ?: 'Variable' }}</p>
                    </div>
                    <div class="group p-5 bg-black/40 rounded-3xl border border-zinc-800 hover:border-amber-500/30 transition-colors">
                        <p class="text-[10px] text-zinc-500 font-black uppercase tracking-widest mb-2 group-hover:text-amber-500">Quality</p>
                        <p class="text-2xl font-black text-white">320kbps</p>
                    </div>
                </div>

                @if($stem->description)
                    <div class="mt-10 pt-8 border-t border-zinc-800/50">
                        <h4 class="text-zinc-500 text-[10px] font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-align-left text-amber-500"></i> Producer Notes
                        </h4>
                        <p class="text-zinc-400 text-base leading-relaxed font-medium">
                            {{ $stem->description }}
                        </p>
                    </div>
                @endif
            </div>

            {{-- New: Licensing & Usage Card --}}
            <div class="bg-gradient-to-br from-zinc-900 to-black border border-zinc-800 rounded-[32px] p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <i class="fa-solid fa-shield-halved text-7xl text-white"></i>
                </div>
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    <div class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center border border-amber-500/20 shrink-0">
                        <i class="fa-solid fa-certificate text-amber-500 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white text-xl font-bold uppercase tracking-tight text-center md:text-left">Creator Friendly License</h3>
                        <p class="text-zinc-500 text-sm mt-3 leading-relaxed text-center md:text-left">
                            This music is safe for use in YouTube, Twitch, and Social Media content.
                            <strong>Credit Required:</strong> Simply include the track title and artist in your video description.
                        </p>
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
                <h4 class="text-[10px] text-zinc-600 font-black uppercase tracking-widest mb-6">Vault Stats</h4>
                <div class="grid grid-cols-2 gap-8 relative">
                    <div class="absolute inset-y-4 left-1/2 w-[1px] bg-zinc-800"></div>
                    <div>
                        <p class="text-3xl font-black text-white">{{ number_format($stem->download_count) }}</p>
                        <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-tighter mt-2">Downloads</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-white">{{ number_format($stem->view_count) }}</p>
                        <p class="text-[9px] text-zinc-500 font-bold uppercase tracking-tighter mt-2">Vault Views</p>
                    </div>
                </div>
            </div>

            {{-- Interactive Keywords --}}
            @if ($stem->tags_keywords)
                <div class="bg-zinc-900/40 border border-zinc-800 rounded-[32px] p-8 backdrop-blur-sm">
                    <h4 class="text-[10px] text-zinc-500 font-black uppercase tracking-widest mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-tags text-amber-500"></i> Metadata Tags
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach (explode(',', $stem->tags_keywords) as $tag)
                            <a href="#" class="px-4 py-2 bg-black rounded-xl text-[11px] font-bold text-zinc-400 border border-zinc-800 hover:border-amber-500/50 hover:text-white transition-all">
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
                    <div class="flex bg-black/50 border border-zinc-800 rounded-xl p-2 items-center">
                        <input type="text" readonly value="{{ url()->current() }}" id="copyUrl" class="bg-transparent border-none text-zinc-500 text-xs flex-1 focus:ring-0 truncate px-2">
                        <button onclick="copyLink()" class="ml-2 bg-amber-500 text-black px-4 py-2 rounded-lg text-[10px] font-black uppercase hover:bg-white transition-colors">
                            Copy
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-webapp-layout>

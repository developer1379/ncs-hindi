<nav class="lg:hidden fixed bottom-6 left-4 right-4 h-20 bg-zinc-950/80 border border-zinc-800/50 rounded-[2.5rem] px-2 flex items-center justify-around z-[100] backdrop-blur-2xl shadow-2xl shadow-black/50">

    {{-- Vault Feed --}}
    <a href="{{ route('home') }}"
        class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 {{ request()->routeIs('home') ? 'text-amber-500 bg-amber-500/10' : 'text-zinc-500' }}">
        <i class="fa-solid fa-layer-group text-xl"></i>
        <span class="text-[7px] font-black uppercase tracking-[0.15em] mt-1">Vault</span>
    </a>

    {{-- Community Chat --}}
    <a href="{{ route('webapp.community.chat') }}"
        class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 {{ request()->routeIs('webapp.community.chat*') ? 'text-amber-500 bg-amber-500/10' : 'text-zinc-500' }}">
        <div class="relative">
            <i class="fa-solid fa-comments text-xl"></i>
            {{-- Optional Notification Dot --}}
            <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-zinc-950"></span>
        </div>
        <span class="text-[7px] font-black uppercase tracking-[0.15em] mt-1">Chat</span>
    </a>

    {{-- Floating Center Action: Create --}}
    <div class="relative -mt-14">
        <a href="{{ route('webapp.forum.create') }}"
           class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-amber-500 via-amber-600 to-red-600 rounded-[22px] shadow-[0_10px_30px_rgba(245,158,11,0.3)] border-[5px] border-[#08080a] text-white active:scale-90 transition-all duration-300">
            <i class="fa-solid fa-plus text-2xl"></i>
        </a>
    </div>

    {{-- Music Library --}}
    <a href="{{ route('webapp.streams') }}"
        class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 {{ request()->routeIs('webapp.streams') ? 'text-amber-500 bg-amber-500/10' : 'text-zinc-500' }}">
        <i class="fa-solid fa-box-open text-xl"></i>
        <span class="text-[7px] font-black uppercase tracking-[0.15em] mt-1">Stems</span>
    </a>

    {{-- User Studio --}}
    <a href="{{ route('webapp.profile') }}"
        class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 {{ request()->routeIs('webapp.profile') ? 'text-amber-500 bg-amber-500/10' : 'text-zinc-500' }}">
        @auth
            @php
                $userAvatar = Auth::user()->profile_image && strlen(Auth::user()->profile_image) > 20
                    ? Auth::user()->profile_image
                    : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=18181b&color=f59e0b&bold=true';
            @endphp
            <img src="{{ $userAvatar }}"
                 class="w-6 h-6 rounded-lg object-cover {{ request()->routeIs('webapp.profile') ? 'ring-2 ring-amber-500' : 'opacity-70' }}"
                 alt="Profile">
        @else
            <i class="fa-solid fa-user-circle text-xl"></i>
        @endauth
        <span class="text-[7px] font-black uppercase tracking-[0.15em] mt-1">Studio</span>
    </a>
</nav>

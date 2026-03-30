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

<div id="notificationGateModal"
    class="fixed inset-0 z-[200] hidden items-center justify-center px-4 py-6 bg-black/70 backdrop-blur-sm">
    <div class="absolute inset-0" data-notification-dismiss></div>
    <div class="relative w-full max-w-lg rounded-[28px] border border-zinc-800 bg-[#0f0f12] text-white shadow-2xl shadow-black/50 overflow-hidden">
        <div class="flex items-start justify-between gap-4 border-b border-zinc-800 px-4 md:px-5 py-4">
            <div>
                <p class="text-[10px] uppercase tracking-[0.25em] text-amber-400 font-black">Notifications</p>
                <h5 class="font-brand text-2xl font-black uppercase tracking-tight">Stay updated</h5>
            </div>
            <button type="button" class="text-zinc-400 hover:text-white transition" data-notification-dismiss aria-label="Close">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="p-4 md:p-5">
            <div class="rounded-[24px] bg-black/40 border border-zinc-800 p-4 md:p-5">
                <div class="flex items-start gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-bell text-amber-400"></i>
                    </div>
                    <div class="min-w-0">
                        <h6 id="notificationGateTitle" class="text-lg font-black text-white uppercase tracking-tight">
                            Get release alerts
                        </h6>
                        <p id="notificationGateDescription" class="mt-2 text-sm text-zinc-400 leading-relaxed">
                            Allow notifications so you can get updates when new music is added.
                        </p>
                        <p id="notificationGateMusic" class="mt-3 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"></p>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex flex-col sm:flex-row gap-3">
                <button type="button" id="notificationGateAllow"
                    class="btn-vault flex-1 px-4 py-3 rounded-2xl text-[10px] font-black tracking-[0.2em] uppercase">
                    Allow notifications
                </button>
                <button type="button" id="notificationGateContinue"
                    class="flex-1 px-4 py-3 rounded-2xl bg-white/5 border border-zinc-800 text-[10px] font-black tracking-[0.2em] uppercase text-zinc-200 hover:border-amber-500/40 hover:text-white transition">
                    Continue
                </button>
            </div>

            <button type="button" id="notificationGateLater"
                class="mt-3 w-full text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 hover:text-zinc-300 transition">
                Not now
            </button>
        </div>
    </div>
</div>

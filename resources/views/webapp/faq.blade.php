<x-webapp-layout title="{{ $page['title'] }}">
    <div class="max-w-6xl mx-auto pb-24">
        <section class="relative overflow-hidden rounded-[36px] border border-white/5 bg-gradient-to-br from-[#0f0b08] via-[#09090b] to-[#030303] p-6 md:p-8 lg:p-10 mb-8">
            <div class="absolute inset-0 opacity-30 bg-[radial-gradient(circle_at_top_right,_rgba(245,158,11,0.18),_transparent_28%),radial-gradient(circle_at_bottom_left,_rgba(153,27,27,0.18),_transparent_30%)]"></div>
            <div class="relative">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-[9px] font-black uppercase tracking-[0.22em] text-amber-400 mb-5">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    Support
                </div>
                <h1 class="font-brand text-3xl md:text-5xl font-black uppercase tracking-tighter text-white leading-[0.92] max-w-3xl">
                    {{ $page['title'] }}
                </h1>
                <p class="mt-4 max-w-3xl text-zinc-300 leading-relaxed text-sm md:text-base">
                    {{ $page['intro'] }}
                </p>
            </div>
        </section>

        <div class="grid lg:grid-cols-[1fr_0.9fr] gap-6 md:gap-8">
            <section class="space-y-6">
                <div class="rounded-[30px] border border-white/5 bg-black/35 p-6 md:p-8">
                    <div class="flex items-center justify-between gap-4 mb-5">
                        <div>
                            <h2 class="font-brand text-xl md:text-2xl font-black uppercase tracking-tight text-white">FAQ</h2>
                            <p class="mt-1 text-[11px] text-zinc-500">Common questions answered by the team.</p>
                        </div>
                        <span class="text-[9px] uppercase tracking-[0.2em] text-zinc-600 font-black">Editable in admin</span>
                    </div>

                    <div class="prose prose-invert max-w-none prose-headings:font-brand prose-headings:uppercase prose-headings:tracking-tight prose-headings:text-white prose-p:text-zinc-300 prose-p:leading-relaxed prose-a:text-amber-400 hover:prose-a:text-amber-300">
                        {!! $page['faq_content'] ?: '<p>Add FAQ content from <strong>Admin &gt; Page Settings</strong>.</p>' !!}
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <div class="rounded-[30px] border border-white/5 bg-black/35 p-6 md:p-8">
                    <div class="flex items-center justify-between gap-4 mb-5">
                        <div>
                            <h2 class="font-brand text-xl md:text-2xl font-black uppercase tracking-tight text-white">{{ $page['legal_title'] }}</h2>
                            <p class="mt-1 text-[11px] text-zinc-500">{{ $page['legal_intro'] }}</p>
                        </div>
                    </div>

                    <div class="prose prose-invert max-w-none prose-headings:font-brand prose-headings:uppercase prose-headings:tracking-tight prose-headings:text-white prose-p:text-zinc-300 prose-p:leading-relaxed prose-li:text-zinc-300 prose-a:text-amber-400 hover:prose-a:text-amber-300">
                        {!! $page['legal_content'] ?: '<p>Add legal guide content from <strong>Admin &gt; Page Settings</strong>.</p>' !!}
                    </div>
                </div>

                <div class="rounded-[30px] border border-amber-500/20 bg-amber-500/5 p-6 md:p-8">
                    <h3 class="font-brand text-lg font-black uppercase tracking-tight text-white">Quick note</h3>
                    <p class="mt-3 text-sm text-zinc-300 leading-relaxed">
                        Use the content fields in admin to keep the FAQ and legal guidance updated whenever policy or usage terms change.
                    </p>
                </div>
            </aside>
        </div>
    </div>
</x-webapp-layout>

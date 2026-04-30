<x-webapp-layout>
    <div class="max-w-4xl mx-auto pb-24">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('home') }}"
                class="w-10 h-10 rounded-xl bg-zinc-900 flex items-center justify-center text-zinc-500 hover:text-white transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="font-brand text-2xl font-black text-white uppercase italic tracking-tighter">Report <span class="text-amber-500">a Bug</span></h1>
                <p class="text-[10px] text-zinc-600 font-black tracking-[0.3em] uppercase mt-2">Help us fix website issues faster</p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-3xl border border-emerald-500/20 bg-emerald-500/10 px-5 py-4 text-sm text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-3xl border border-red-500/20 bg-red-500/10 px-5 py-4 text-sm text-red-200">
                Please fix the highlighted fields and submit again.
            </div>
        @endif

        <form action="{{ route('webapp.bug-reports.store') }}" method="POST" class="forum-card p-8 lg:p-12 space-y-8 bg-[#0a0a0c]">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Issue title</label>
                    <input name="title" type="text" value="{{ old('title') }}" required
                        class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1"
                        placeholder="Example: Download button does not work on mobile">
                    @error('title')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Page URL</label>
                    <input name="page_url" id="page_url" type="url" value="{{ old('page_url') }}"
                        class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1"
                        placeholder="https://example.com/page">
                    @error('page_url')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">What happened?</label>
                    <textarea name="description" rows="8" required
                        class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1"
                        placeholder="Tell us what you expected, what actually happened, and how we can reproduce it.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="rounded-[2rem] border border-zinc-800 bg-black/40 p-5 text-sm text-zinc-400">
                Include useful details like device type, browser, login state, and the exact steps that caused the issue.
            </div>

            <div class="pt-6 border-t border-zinc-900 flex justify-end gap-4">
                <a href="{{ route('home') }}" class="px-8 py-3 rounded-xl border border-zinc-800 text-[10px] font-bold text-zinc-500 hover:text-white transition uppercase">Cancel</a>
                <button type="submit" class="btn-vault px-10 py-3 text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-900/20">
                    Submit Report
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const pageUrlInput = document.getElementById('page_url');

                if (pageUrlInput && !pageUrlInput.value) {
                    pageUrlInput.value = window.location.href;
                }
            });
        </script>
    @endpush
</x-webapp-layout>

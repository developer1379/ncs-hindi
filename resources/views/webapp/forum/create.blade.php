<x-webapp-layout>
    <style>
        /* Custom Styles for TinyMCE containers in NCS Theme */
        .tox-tinymce {
            border: 1px solid var(--border) !important;
            border-radius: 16px !important;
            overflow: hidden !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
            background: var(--bg) !important;
        }

        .tox .tox-toolbar, .tox .tox-toolbar__overflow, .tox .tox-toolbar__primary {
            background-color: var(--panel) !important;
            border-bottom: 1px solid var(--border) !important;
        }

        .tox .tox-edit-area__iframe {
            background-color: var(--bg) !important;
        }

        html.light .tox-tinymce {
            border-color: var(--border) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02) !important;
        }

        /* Light Mode Premium create thread Overrides */
        html.light .max-w-4xl h1.text-white {
            color: #09090b !important;
        }
        html.light .max-w-4xl a.bg-zinc-900 {
            background-color: var(--panel) !important;
            border: 1px solid var(--border) !important;
            color: var(--text) !important;
        }
        html.light .max-w-4xl a.bg-zinc-900:hover {
            color: #b45309 !important;
            border-color: #b45309 !important;
        }
        html.light .max-w-4xl input.bg-black,
        html.light .max-w-4xl select.bg-black {
            background-color: var(--card) !important;
            border-color: var(--border) !important;
            color: #09090b !important;
        }
        html.light .max-w-4xl input.bg-black:focus,
        html.light .max-w-4xl select.bg-black:focus {
            border-color: #b45309 !important;
        }
        html.light .forum-card.bg-\[\#0a0a0c\] {
            background-color: var(--card) !important;
            border-color: var(--border) !important;
        }
        html.light .border-dashed.border-zinc-800 {
            background-color: var(--panel) !important;
            border-color: var(--border) !important;
        }
        html.light .border-dashed.border-zinc-800 h4.text-white {
            color: #09090b !important;
        }
        html.light .border-dashed.border-zinc-800 div.bg-zinc-900 {
            background-color: var(--card) !important;
            border: 1px solid var(--border) !important;
            color: #b45309 !important;
        }
        html.light .border-dashed.border-zinc-800 button {
            border-color: var(--border) !important;
            color: var(--text) !important;
        }
        html.light .border-dashed.border-zinc-800 button:hover {
            background-color: var(--card) !important;
            color: #b45309 !important;
            border-color: #b45309 !important;
        }
    </style>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('home') }}"
                class="w-10 h-10 rounded-xl bg-zinc-900 flex items-center justify-center text-zinc-500 hover:text-white transition">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <h1 class="font-brand text-2xl font-black text-white uppercase italic tracking-tighter">New <span
                    class="text-amber-500">Feed Post</span></h1>
        </div>

        <form id="vault-post-form" action="{{ route('webapp.forum.store') }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="forum-card p-6 sm:p-8 lg:p-10 bg-[#0a0a0c]">
                <div class="space-y-6">
                    {{-- Title --}}
                    <div>
                        <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Thread Title</label>
                        <input name="title" type="text" required
                            class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-base sm:text-lg font-bold text-white focus:border-amber-600 outline-none transition mt-1"
                            placeholder="e.g. 'Tum Mile' Official music Release">
                    </div>

                    {{-- Category & Metadata Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Category</label>
                            <select name="category_id"
                                class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-zinc-400 focus:border-amber-600 outline-none transition mt-1 appearance-none">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- TinyMCE Description --}}
                    <div>
                        <label class="text-[10px] font-black text-zinc-600 uppercase tracking-widest ml-2">Content / Technical Details</label>
                        <div class="mt-1">
                            <textarea name="content" id="editor-container" class="w-full bg-black border border-zinc-800 rounded-xl p-4 text-sm text-white focus:border-amber-600 outline-none transition mt-1" rows="10" placeholder="Describe your music, share production secrets, or add mixing tips..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- File Upload Card --}}
            <div class="forum-card p-6 sm:p-8 border-dashed border-zinc-800 bg-zinc-900/20">
                <div class="flex flex-col items-center justify-center text-center py-4">
                    <div class="w-16 h-16 rounded-2xl bg-zinc-900 flex items-center justify-center mb-4 text-amber-500">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                    </div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-tight">Upload music Zip</h4>
                    <input type="file" name="stem_file" class="hidden" id="stem_file">
                    <button type="button" onclick="document.getElementById('stem_file').click()"
                        class="mt-6 px-6 py-2 rounded-xl border border-zinc-700 text-[10px] font-black text-zinc-400 hover:text-white hover:bg-zinc-800 transition uppercase tracking-widest">
                        Select File
                    </button>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button type="submit"
                    class="w-full sm:w-auto btn-vault px-12 py-4 text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-red-900/30">
                    Publish to Feeds
                </button>
            </div>
        </form>
    </div>

    {{-- TinyMCE Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '#editor-container',
                license_key: 'gpl',
                height: 380,
                menubar: false,
                statusbar: false,
                plugins: 'lists link image media code wordcount',
                toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image media | code',
                skin: document.documentElement.classList.contains('light') ? 'oxide' : 'oxide-dark',
                skin_url: document.documentElement.classList.contains('light')
                    ? 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/skins/ui/oxide'
                    : 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/skins/ui/oxide-dark',
                content_css: document.documentElement.classList.contains('light')
                    ? 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/skins/content/default/content.min.css'
                    : 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/skins/content/dark/content.min.css',
                content_style: document.documentElement.classList.contains('light')
                    ? 'body { font-family: "Inter", sans-serif; font-size: 14px; background-color: #ffffff; color: #09090b; }'
                    : 'body { font-family: "Inter", sans-serif; font-size: 14px; background-color: #050505; color: #efefef; }',
                images_upload_handler: function (blobInfo, progress) {
                    return new Promise(function (resolve, reject) {
                        var xhr, formData;
                        xhr = new XMLHttpRequest();
                        xhr.withCredentials = false;
                        xhr.open('POST', '{{ route('webapp.upload-image') }}');
                        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                        xhr.upload.onprogress = function (e) {
                            progress(e.loaded / e.total * 100);
                        };

                        xhr.onload = function() {
                            var json;
                            if (xhr.status === 403) {
                                reject('HTTP Error: ' + xhr.status, { remove: true });
                                return;
                            }
                            if (xhr.status < 200 || xhr.status >= 300) {
                                reject('HTTP Error: ' + xhr.status);
                                return;
                            }
                            json = JSON.parse(xhr.responseText);
                            if (!json || typeof json.location != 'string') {
                                reject('Invalid JSON: ' + xhr.responseText);
                                return;
                            }
                            resolve(json.location);
                        };

                        xhr.onerror = function () {
                            reject('Image upload failed due to a network error.');
                        };

                        formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                        xhr.send(formData);
                    });
                },
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                    });
                    editor.on('OpenWindow', function (e) {
                        setTimeout(function () {
                            var tabs = document.querySelectorAll('.tox-tab');
                            for (var i = 0; i < tabs.length; i++) {
                                if (tabs[i].textContent.trim().toLowerCase() === 'upload') {
                                    tabs[i].click();
                                    break;
                                }
                            }
                        }, 50);
                    });
                }
            });
        });
    </script>
</x-webapp-layout>

<x-app-layout title="Edit Release | NCS Hindi Admin">
    @push('heads')
        <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @endpush

    @php
        $languageOptions = [
            'Hindi',
            'English',
            'Punjabi',
            'Bengali',
            'Tamil',
            'Telugu',
            'Marathi',
            'Gujarati',
            'Kannada',
            'Malayalam',
            'Urdu',
            'Instrumental',
        ];
        $selectedLanguages = collect(explode(',', (string) $stem->language))
            ->map(fn ($language) => trim($language))
            ->filter()
            ->values()
            ->all();
    @endphp

    <div class="py-4">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('admin.stems.index') }}"
                                    class="text-decoration-none text-primary">Inventory</a></li>
                            <li class="breadcrumb-item active text-dark">Edit Release</li>
                        </ol>
                    </nav>
                    <h3 class="fw-bold text-dark">Edit: {{ $stem->title }}</h3>
                    <p class="text-muted mb-0">Modify metadata or replace studio assets in the vault.</p>
                </div>
            </div>

            <form id="stemUpdateForm" action="{{ route('admin.stems.update', $stem->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark"><iconify-icon icon="mdi:music-box-outline"
                                        class="me-2 text-primary"></iconify-icon>Music Metadata</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-12 mb-2">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title"
                                            class="form-control form-control-lg bg-light border-0"
                                            value="{{ $stem->title }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Artist
                                            Name</label>
                                        <input type="text" name="artist_name" class="form-control bg-light border-0"
                                            value="{{ $stem->artist_name }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Description</label>
                                        <textarea name="description" rows="4" class="form-control bg-light border-0"
                                            placeholder="Write a short release description, credits, or usage notes...">{{ $stem->description }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">License Text</label>
                                        <textarea name="license_text" rows="6" class="form-control bg-light border-0"
                                            placeholder="Write the full creator-friendly license text shown on the release page...">{{ old('license_text', $stem->license_text) }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Languages
                                            <span class="text-muted">(multi-select)</span></label>
                                        <div id="language_options" class="d-flex flex-wrap gap-2">
                                            @foreach ($languageOptions as $language)
                                                <input type="checkbox" class="btn-check"
                                                    id="lang_{{ \Illuminate\Support\Str::slug($language) }}"
                                                    value="{{ $language }}" autocomplete="off"
                                                    {{ in_array($language, $selectedLanguages, true) ? 'checked' : '' }}>
                                                <label for="lang_{{ \Illuminate\Support\Str::slug($language) }}"
                                                    class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold small text-nowrap">
                                                    {{ $language }}
                                                </label>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="language" id="language" value="{{ $stem->language }}">
                                        <small class="text-muted mt-2 d-block">Pick one or more languages; we’ll store them in the release metadata.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Category
                                            <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select bg-light border-0" required>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $stem->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Key</label>
                                        <input type="text" name="music_key" class="form-control bg-light border-0"
                                            value="{{ $stem->music_key }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Visibility</label>
                                        <select name="is_public" class="form-select bg-light border-0">
                                            <option value="1" {{ $stem->is_public ? 'selected' : '' }}>Public
                                            </option>
                                            <option value="0" {{ !$stem->is_public ? 'selected' : '' }}>Private
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark"><iconify-icon icon="mdi:cloud-link"
                                        class="me-2 text-primary"></iconify-icon>Storage Link</h5>
                            </div>
                            <div class="card-body p-4">
                                <label class="form-label fw-bold small text-uppercase text-secondary">Mega.nz Link <span
                                        class="text-danger">*</span></label>
                                <input type="url" name="mega_link" id="mega_link"
                                    class="form-control bg-light border-0" value="{{ $stem->mega_link }}"
                                    placeholder="https://mega.nz/file/..." required>
                                <small class="text-muted mt-2 d-block">Updating this will override the previous link in
                                    the vault.</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 mb-4 text-center overflow-hidden">
                            <div class="card-header bg-white border-bottom py-3 text-start fw-bold">Cover Art</div>
                            <div class="bg-light d-flex align-items-center justify-content-center border-bottom"
                                style="height: 250px;">
                                <img id="imagePreview" src="{{ $stem->featured_image }}" alt="Cover Art Preview"
                                    class="w-100 h-100"
                                    style="{{ $stem->featured_image ? '' : 'display: none;' }} object-fit: cover;">
                                <div id="previewPlaceholder" class="p-4"
                                    style="{{ $stem->featured_image ? 'display: none;' : '' }}">
                                    <iconify-icon icon="mdi:image-album" width="48"
                                        class="text-secondary opacity-50"></iconify-icon>
                                </div>
                            </div>
                            <div class="p-3 text-start">
                                <label class="small text-muted mb-2 d-block">Upload new to replace existing
                                    artwork</label>
                                <input type="file" name="featured_image" id="featured_image"
                                    class="form-control form-control-sm border-0 bg-light" accept="image/*">
                            </div>
                        </div>

                        <div id="uploadProgressContainer" class="mb-4" style="display: none;">
                            <div class="progress" style="height: 8px;">
                                <div id="uploadProgressBar"
                                    class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="btnSubmit"
                            class="btn btn-success btn-lg w-100 py-3 rounded-3 shadow fw-bold text-uppercase">
                            <iconify-icon icon="mdi:check-circle" class="me-2"></iconify-icon> Update Release
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script>
            $(document).ready(function() {
                // Image Preview logic
                $('#featured_image').on('change', function() {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        $('#imagePreview').attr('src', e.target.result).show();
                        $('#previewPlaceholder').hide();
                    };
                    if (this.files[0]) reader.readAsDataURL(this.files[0]);
                });

                // Unified AJAX Submission (Matches Create Logic)
                const syncLanguageField = () => {
                    const selectedLanguages = $('#language_options input[type="checkbox"]:checked').map(function() {
                        return $(this).val();
                    }).get();
                    $('#language').val(selectedLanguages.join(', '));
                };

                $('#language_options').on('change', 'input[type="checkbox"]', syncLanguageField);

                $('#stemUpdateForm').on('submit', function(e) {
                    e.preventDefault();
                    syncLanguageField();

                    const megaLink = $('#mega_link').val();
                    if (!megaLink) {
                        toastr.error('Mega Link is required.');
                        return false;
                    }

                    let formData = new FormData(this);

                    // MANUALLY SEND MEGA LINK AS STEM_FILE (Mapping for Backend)
                    formData.append('stem_file', megaLink);

                    let $btn = $('#btnSubmit');
                    let $progress = $('#uploadProgressContainer');

                    $btn.prop('disabled', true).html(
                        '<iconify-icon icon="line-md:loading-twotone-loop" class="me-2"></iconify-icon> Updating...'
                        );

                    // Only show progress if a new featured image is being uploaded
                    if ($('#featured_image').val()) {
                        $progress.slideDown();
                    }

                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST', // Form handles Method Spoofing (@method('PUT'))
                        data: formData,
                        processData: false,
                        contentType: false,
                        xhr: function() {
                            let xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    let p = Math.round((evt.loaded / evt.total) * 100);
                                    $('#uploadProgressBar').css('width', p + '%');
                                }
                            }, false);
                            return xhr;
                        },
                        success: function(res) {
                            toastr.success('Release updated successfully!');
                            setTimeout(() => window.location.href =
                                "{{ route('admin.stems.index') }}", 1500);
                        },
                        error: function(xhr) {
                            $btn.prop('disabled', false).html(
                                '<iconify-icon icon="mdi:check-circle" class="me-2"></iconify-icon> Update Release'
                                );
                            $progress.hide();

                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    toastr.error(value[0]);
                                });
                            } else {
                                toastr.error(
                                    'Update failed. Check your connection or server limits.');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>

<x-app-layout title="Upload Official Music | NCS Hindi Admin">
    @push('heads')
        <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @endpush

    @php
    @endphp

    <div class="py-4">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('admin.music.index') }}"
                                    class="text-decoration-none text-primary">Music Inventory</a></li>
                            <li class="breadcrumb-item active text-dark">New Release</li>
                        </ol>
                    </nav>
                    <h3 class="fw-bold text-dark">Initialize Music Release</h3>
                    <p class="text-muted mb-0">Fill in metadata and provide an .mp3 or a cloud storage link.</p>
                </div>
            </div>

            <form id="musicUploadForm" action="{{ route('admin.music.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <iconify-icon icon="mdi:music-box-outline"
                                        class="me-2 text-primary"></iconify-icon>Music Metadata
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-12 mb-2">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title"
                                            class="form-control form-control-lg bg-light border-0"
                                            placeholder="e.g., Baarishein - Lo-Fi" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Artist
                                            Name</label>
                                        <input type="text" name="artist_name" class="form-control bg-light border-0"
                                            placeholder="e.g., Anuv Jain">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Description</label>
                                        <textarea name="description" rows="4" class="form-control bg-light border-0"
                                            placeholder="Write a short release description, credits, or usage notes..."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Languages
                                            <span class="text-muted">(multi-select)</span></label>
                                        <div id="language_options" class="d-flex flex-wrap gap-2">
                                            @foreach ($languages as $language)
                                                <input type="checkbox" class="btn-check"
                                                    id="lang_{{ \Illuminate\Support\Str::slug($language) }}"
                                                    value="{{ $language }}" autocomplete="off">
                                                <label for="lang_{{ \Illuminate\Support\Str::slug($language) }}"
                                                    class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold small text-nowrap">
                                                    {{ $language }}
                                                </label>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="language" id="language">
                                        <small class="text-muted mt-2 d-block">Pick one or more languages; we’ll store them in the release metadata.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Category
                                            <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select bg-light border-0" required>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Key</label>
                                        <input type="text" name="music_key" class="form-control bg-light border-0"
                                            placeholder="Am, C#m">
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Visibility</label>
                                        <select name="is_public" class="form-select bg-light border-0">
                                            <option value="1">Public</option>
                                            <option value="0">Private</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <iconify-icon icon="mdi:cloud-link" class="me-2 text-primary"></iconify-icon>Storage
                                    Link
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase text-secondary">Mega.nz Link <span
                                            class="text-danger">*</span></label>
                                    <input type="url" name="mega_link" id="mega_link"
                                        class="form-control bg-light border-0" placeholder="https://mega.nz/file/..."
                                        required>
                                </div>
                                <div>
                                    <label class="form-label fw-bold small text-uppercase text-secondary">YouTube Link <span
                                            class="text-muted">(Optional)</span></label>
                                    <input type="url" name="youtube_link" id="youtube_link"
                                        class="form-control bg-light border-0" placeholder="https://youtube.com/watch?v=...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden text-center">
                            <div class="card-header bg-white border-bottom py-3 text-start fw-bold">Cover Art</div>
                            <div id="imagePreviewContainer"
                                class="bg-light d-flex align-items-center justify-content-center border-bottom"
                                style="height: 250px;">
                                <img id="imagePreview" src="" class="w-100 h-100"
                                    style="display: none; object-fit: cover;">
                                <div id="previewPlaceholder" class="p-4">
                                    <iconify-icon icon="mdi:image-album" width="48"
                                        class="text-secondary opacity-50"></iconify-icon>
                                </div>
                            </div>
                            <div class="p-3">
                                <input type="file" name="featured_image" id="featured_image"
                                    class="form-control form-control-sm border-0 bg-light" accept="image/*">
                            </div>
                        </div>

                        <button type="submit" id="btnSubmit"
                            class="btn btn-primary btn-lg w-100 py-3 rounded-3 shadow fw-bold text-uppercase">
                            <iconify-icon icon="mdi:cloud-upload" class="me-2"></iconify-icon> Publish Music
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                const $form = $('#musicUploadForm');
                const $btn = $('#btnSubmit');

                const syncLanguageField = () => {
                    const selectedLanguages = $('#language_options input[type="checkbox"]:checked').map(function() {
                        return $(this).val();
                    }).get();
                    $('#language').val(selectedLanguages.join(', '));
                };

                // Image Preview
                $('#featured_image').on('change', function() {
                    const file = this.files[0];
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = (e) => {
                            $('#imagePreview').attr('src', e.target.result).show();
                            $('#previewPlaceholder').hide();
                        };
                        reader.readAsDataURL(file);
                    }
                });

                $('#language_options').on('change', 'input[type="checkbox"]', syncLanguageField);

                $form.on('submit', function(e) {
                    e.preventDefault();
                    syncLanguageField();

                    const megaLink = $('#mega_link').val();
                    if (!megaLink) {
                        toastr.error('Mega Link is required.');
                        return false;
                    }

                    let formData = new FormData(this);

                    // This ensures the backend 'music_file' validation/field receives the URL
                    formData.append('music_file', megaLink);

                    $btn.prop('disabled', true).html(
                        '<iconify-icon icon="line-md:loading-twotone-loop" class="me-2"></iconify-icon> Publishing...'
                        );

                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            toastr.success('Success! Music asset published.');
                            setTimeout(() => window.location.href =
                                "{{ route('admin.music.index') }}", 1500);
                        },
                        error: function(xhr) {
                            $btn.prop('disabled', false).html(
                                '<iconify-icon icon="mdi:cloud-upload" class="me-2"></iconify-icon> Publish Music'
                                );

                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    toastr.error(value[0]);
                                });
                            } else {
                                toastr.error('Server error. Please check your connection.');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>








<x-app-layout title="Upload Official Stem | NCS Hindi Admin">
    @push('heads')
        <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @endpush

    <div class="py-4">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('admin.stems.index') }}"
                                    class="text-decoration-none text-primary">Inventory</a></li>
                            <li class="breadcrumb-item active text-dark">New Release</li>
                        </ol>
                    </nav>
                    <h3 class="fw-bold text-dark">Initialize Official Release</h3>
                    <p class="text-muted mb-0">Fill in metadata and provide an .mp3 or a cloud storage link.</p>
                </div>
            </div>

            <form id="stemUploadForm" action="{{ route('admin.stems.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
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
                                            placeholder="e.g., Baarishein - Lo-Fi" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Artist
                                            Name</label>
                                        <input type="text" name="artist_name" class="form-control bg-light border-0"
                                            placeholder="e.g., Anuv Jain">
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Album/Movie
                                            Name</label>
                                        <input type="text" name="album_movie_name"
                                            class="form-control bg-light border-0" placeholder="e.g., Indie Hits 2026">
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
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Language</label>
                                        <input type="text" name="language" class="form-control bg-light border-0"
                                            placeholder="e.g., Hindi">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark"><iconify-icon icon="mdi:cloud-link"
                                        class="me-2 text-primary"></iconify-icon>External Storage</h5>
                            </div>
                            <div class="card-body p-4">
                                <label class="form-label fw-bold small text-uppercase text-secondary">Mega.nz
                                    Link</label>
                                <input type="url" name="mega_link" id="mega_link"
                                    class="form-control bg-light border-0" placeholder="https://mega.nz/file/...">
                                <small class="text-muted mt-2 d-block">Provide a link if you aren't uploading a file
                                    directly.</small>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark"><iconify-icon icon="mdi:tune-vertical"
                                        class="me-2 text-primary"></iconify-icon>Musical Details</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">BPM</label>
                                        <input type="number" name="bpm" class="form-control bg-light border-0"
                                            placeholder="128">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Key</label>
                                        <input type="text" name="music_key" class="form-control bg-light border-0"
                                            placeholder="Am, C#m">
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            class="form-label fw-bold small text-uppercase text-secondary">Visibility</label>
                                        <select name="is_public" class="form-select bg-light border-0">
                                            <option value="1">Public</option>
                                            <option value="0">Private</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase text-secondary">Music
                                            Description</label>
                                        <textarea name="description" class="form-control bg-light border-0" rows="3"></textarea>
                                    </div>
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

                        <div class="card border-0 shadow-sm rounded-4 mb-4 text-center">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-uppercase small mb-3">Audio (.mp3)</h6>
                                <input type="file" name="stem_file" id="stem_file"
                                    class="form-control border-0 bg-light" accept=".mp3">
                                <p class="text-muted small mt-2 mb-0">Optional if Mega Link is provided.</p>
                            </div>
                        </div>

                        <div id="uploadProgressContainer" class="mb-4" style="display: none;">
                            <div class="d-flex justify-content-between mb-1 small fw-bold text-primary">
                                <span id="statusText">Uploading...</span>
                                <span id="uploadPercentage">0%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div id="uploadProgressBar"
                                    class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">
                                </div>
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
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            $(document).ready(function() {
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

                // Custom validation: Must have either a file OR a mega link
                $("#stemUploadForm").validate({
                    rules: {
                        title: "required",
                        category_id: "required",
                        mega_link: {
                            url: true,
                            required: function(element) {
                                return $("#stem_file").val() === "";
                            }
                        },
                        stem_file: {
                            required: function(element) {
                                return $("#mega_link").val() === "";
                            },
                            extension: "mp3"
                        }
                    },
                    messages: {
                        mega_link: "Please provide either a Mega link or upload an MP3 file.",
                        stem_file: "Please upload an MP3 file or provide a Mega link."
                    },
                    errorElement: 'span',
                    errorClass: 'text-danger small d-block mt-1',
                    highlight: function(element) {
                        $(element).addClass('border border-danger');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('border border-danger');
                    },

                    submitHandler: function(form, event) {
                        event.preventDefault();
                        let formData = new FormData(form);
                        let $btn = $('#btnSubmit');
                        let $progress = $('#uploadProgressContainer');

                        $btn.prop('disabled', true).text('Processing...');

                        // Only show progress bar if a file is actually being uploaded
                        if ($('#stem_file').val() || $('#featured_image').val()) {
                            $progress.show();
                        }

                        $.ajax({
                            url: $(form).attr('action'),
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            xhr: function() {
                                let xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function(evt) {
                                    if (evt.lengthComputable) {
                                        let p = Math.round((evt.loaded / evt.total) *
                                            100);
                                        $('#uploadProgressBar').css('width', p + '%');
                                        $('#uploadPercentage').text(p + '%');
                                    }
                                }, false);
                                return xhr;
                            },
                            success: function(res) {
                                toastr.success('Success! Music asset published.');
                                setTimeout(() => window.location.href =
                                    "{{ route('admin.stems.index') }}", 1500);
                            },
                            error: function(xhr) {
                                $btn.prop('disabled', false).html(
                                    '<iconify-icon icon="mdi:cloud-upload" class="me-2"></iconify-icon> Publish Music'
                                    );
                                $progress.hide();
                                if (xhr.status === 422) {
                                    $.each(xhr.responseJSON.errors, (k, v) => toastr.error(v[
                                        0]));
                                } else {
                                    toastr.error('Upload failed. Check server limits.');
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>

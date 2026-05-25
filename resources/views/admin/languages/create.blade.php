<x-app-layout title="Add Language | BestBusinessCoachIndia">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fs-18 fw-semibold m-0">Add New Language</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.languages.index') }}">Languages</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form id="createLanguageForm" action="{{ route('admin.languages.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Language Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name') }}" placeholder="e.g. Hindi, English" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Slug (Optional)</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        value="{{ old('slug') }}" placeholder="auto-generated-slug">
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeSwitch" checked>
                                        <label class="form-check-label" for="activeSwitch">Language Status (Active)</label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.languages.index') }}" class="btn btn-light px-4">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Save Language</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Slug Helper
                $('#name').on('keyup', function() {
                    let name = $(this).val();
                    let slug = name.toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
                    $('#slug').val(slug);
                });
            });
        </script>
    @endpush
</x-app-layout>

<x-app-layout title="Languages | BestBusinessCoachIndia">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-center justify-content-between">
                <h4 class="fs-18 fw-semibold m-0">Song Languages</h4>
                <a href="{{ route('admin.languages.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus me-1"></i> Add Language
                </a>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table table-centered mb-0 table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Language Name</th>
                                            <th>Slug</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($languages as $language)
                                            <tr>
                                                <td class="fw-medium text-dark">{{ $language->name }}</td>
                                                <td class="text-muted">{{ $language->slug }}</td>
                                                <td>
                                                    @if ($language->is_active)
                                                        <span class="badge bg-success-subtle text-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.languages.edit', $language->id) }}"
                                                        class="btn btn-sm btn-soft-info">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>

                                                    <form
                                                        action="{{ route('admin.languages.destroy', $language->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-soft-danger"
                                                            onclick="return confirm('Are you sure you want to remove this language?')">
                                                            <i class="mdi mdi-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">No languages found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

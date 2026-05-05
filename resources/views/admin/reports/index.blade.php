<x-app-layout title="Bug Reports | Fitx Admin">
    <div class="content">
        <div class="container-fluid px-3 px-md-4 pb-4">
            <!-- Page Header -->
            <div class="py-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h4 class="fs-4 fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                        <iconify-icon icon="tabler:bug" class="text-primary"></iconify-icon>
                        Bug Reports
                    </h4>
                    <p class="text-muted mb-0 fs-14">Review and manage website issues submitted by users.</p>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="card mb-4 border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('admin.reports.index') }}" method="GET">
                        <div class="row g-3">
                            <!-- Search Input -->
                            <div class="col-12 col-lg-6">
                                <label class="form-label fw-medium text-secondary fs-14">Search Report</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <iconify-icon icon="tabler:search" class="text-muted"></iconify-icon>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                                        placeholder="Search by title, description, URL, user, or email..."
                                        value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- Status Dropdown -->
                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label fw-medium text-secondary fs-14">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>In Review</option>
                                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12 col-md-6 col-lg-3 d-flex align-items-end gap-2 pt-2 pt-md-0">
                                <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                    <iconify-icon icon="tabler:filter"></iconify-icon> Filter
                                </button>
                                <a href="{{ route('admin.reports.index') }}" class="btn btn-light w-100 d-flex align-items-center justify-content-center gap-2 border">
                                    <iconify-icon icon="tabler:refresh"></iconify-icon> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead class="table-light text-secondary fs-14">
                                <tr>
                                    <th class="ps-4 fw-semibold border-bottom-0">Issue</th>
                                    <th class="fw-semibold border-bottom-0">Reported By</th>
                                    <th class="fw-semibold border-bottom-0">Page</th>
                                    <th class="fw-semibold border-bottom-0">Status</th>
                                    <th class="fw-semibold border-bottom-0">Submitted</th>
                                    <th class="pe-4 fw-semibold border-bottom-0 text-end">Admin Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bugReports as $bugReport)
                                    <tr>
                                        <!-- Issue Column -->
                                        <td class="ps-4" style="min-width: 250px; white-space: normal;">
                                            <h5 class="mb-1 fs-15 fw-bold text-dark">{{ $bugReport->title }}</h5>
                                            <p class="mb-0 text-muted fs-13 lh-sm">{{ \Illuminate\Support\Str::limit(strip_tags($bugReport->description), 80) }}</p>
                                        </td>

                                        <!-- User Column -->
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 12px;">
                                                    {{ strtoupper(substr($bugReport->user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold fs-14 text-dark">{{ $bugReport->user->name }}</div>
                                                    <div class="text-muted fs-13">{{ $bugReport->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Page URL Column -->
                                        <td style="max-width: 200px;">
                                            @if ($bugReport->page_url)
                                                <a href="{{ $bugReport->page_url }}" target="_blank" class="text-primary text-decoration-none text-truncate d-inline-block w-100" title="{{ $bugReport->page_url }}">
                                                    <iconify-icon icon="tabler:external-link" class="me-1 align-middle"></iconify-icon>{{ $bugReport->page_url }}
                                                </a>
                                            @else
                                                <span class="text-muted fst-italic fs-13">Not provided</span>
                                            @endif
                                        </td>

                                        <!-- Status Column -->
                                        <td style="min-width: 140px;">
                                            <form action="{{ route('admin.reports.update', $bugReport) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="form-select form-select-sm shadow-none {{ $bugReport->status === 'resolved' ? 'bg-success-subtle text-success border-success-subtle' : ($bugReport->status === 'in_review' ? 'bg-warning-subtle text-warning border-warning-subtle' : '') }}" onchange="this.form.submit()">
                                                    <option value="open" {{ $bugReport->status === 'open' ? 'selected' : '' }}>Open</option>
                                                    <option value="in_review" {{ $bugReport->status === 'in_review' ? 'selected' : '' }}>In Review</option>
                                                    <option value="resolved" {{ $bugReport->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                </select>
                                                <input type="hidden" name="admin_notes" value="{{ $bugReport->admin_notes }}">
                                            </form>
                                        </td>

                                        <!-- Date Column -->
                                        <td>
                                            <div class="fw-medium text-dark fs-14">{{ $bugReport->created_at->format('d M Y') }}</div>
                                            <div class="text-muted fs-13">{{ $bugReport->created_at->diffForHumans() }}</div>
                                        </td>

                                        <!-- Actions Column -->
                                        <td class="pe-4 text-end" style="min-width: 280px; white-space: normal;">
                                            <div class="d-flex flex-column align-items-end gap-2">
                                                <a href="{{ route('admin.reports.show', $bugReport) }}" class="btn btn-sm btn-light border shadow-sm d-inline-flex align-items-center gap-1">
                                                    <iconify-icon icon="tabler:eye"></iconify-icon> View Details
                                                </a>

                                                <form action="{{ route('admin.reports.update', $bugReport) }}" method="POST" class="w-100 m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $bugReport->status }}">
                                                    <div class="input-group input-group-sm shadow-sm">
                                                        <textarea name="admin_notes" rows="1" class="form-control fs-13" style="resize: none;"
                                                            placeholder="Add internal note...">{{ $bugReport->admin_notes }}</textarea>
                                                        <button type="submit" class="btn btn-primary px-2" title="Save Note">
                                                            <iconify-icon icon="tabler:device-floppy"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Empty State -->
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center justify-content-center py-4">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                                    <iconify-icon icon="tabler:bug-off" class="text-muted" style="font-size: 40px;"></iconify-icon>
                                                </div>
                                                <h5 class="fw-bold text-dark mb-1">No bug reports found</h5>
                                                <p class="text-muted mb-0">You're all caught up! There are no bugs matching your criteria.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if ($bugReports->hasPages())
                    <div class="card-footer bg-white border-top p-3 d-flex justify-content-center justify-content-md-end">
                        {{ $bugReports->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

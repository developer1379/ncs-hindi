<x-app-layout title="Bug Reports | Fitx Admin">
    <style>
        /* Optional: Adds a subtle lift effect when hovering over cards */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.08)!important;
        }
    </style>

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

            <!-- Card Grid Layout -->
            <div class="row g-4">
                @forelse($bugReports as $bugReport)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100 border-0 shadow-sm rounded-3 hover-lift d-flex flex-column">

                            <!-- Card Body -->
                            <div class="card-body p-4 flex-grow-1">
                                <!-- Top Row: Date & Status Form -->
                                <div class="d-flex justify-content-between align-items-start mb-3 gap-2">
                                    <div class="text-muted fs-13 d-flex align-items-center gap-1">
                                        <iconify-icon icon="tabler:calendar-event"></iconify-icon>
                                        {{ $bugReport->created_at->format('d M Y') }}
                                    </div>
                                    <form action="{{ route('admin.reports.update', $bugReport) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm shadow-none rounded-pill px-3 py-1 fw-medium fs-13 {{ $bugReport->status === 'resolved' ? 'bg-success-subtle text-success border-success-subtle' : ($bugReport->status === 'in_review' ? 'bg-warning-subtle text-warning border-warning-subtle' : 'bg-danger-subtle text-danger border-danger-subtle') }}" onchange="this.form.submit()">
                                            <option value="open" {{ $bugReport->status === 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="in_review" {{ $bugReport->status === 'in_review' ? 'selected' : '' }}>In Review</option>
                                            <option value="resolved" {{ $bugReport->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        </select>
                                        <input type="hidden" name="admin_notes" value="{{ $bugReport->admin_notes }}">
                                    </form>
                                </div>

                                <!-- Issue Title & Description -->
                                <h5 class="fs-16 fw-bold text-dark mb-2 lh-base">{{ $bugReport->title }}</h5>
                                <p class="text-muted fs-14 mb-4 lh-sm">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($bugReport->description), 100) }}
                                </p>

                                <!-- Page Context (URL) -->
                                <div class="bg-light rounded p-2 mb-4 d-flex align-items-center gap-2">
                                    <iconify-icon icon="tabler:link" class="text-muted"></iconify-icon>
                                    @if ($bugReport->page_url)
                                        <a href="{{ $bugReport->page_url }}" target="_blank" class="text-primary text-decoration-none text-truncate fs-13 fw-medium d-block w-100" title="{{ $bugReport->page_url }}">
                                            {{ $bugReport->page_url }}
                                        </a>
                                    @else
                                        <span class="text-muted fst-italic fs-13 w-100">No URL provided</span>
                                    @endif
                                </div>

                                <!-- Reporter Info -->
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 40px; height: 40px; font-size: 14px;">
                                        {{ strtoupper(substr($bugReport->user->name, 0, 2)) }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="fw-semibold fs-14 text-dark text-truncate">{{ $bugReport->user->name }}</div>
                                        <div class="text-muted fs-13 text-truncate">{{ $bugReport->user->email }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer: Admin Actions -->
                            <div class="card-footer bg-white border-top p-3 d-flex flex-column gap-2 mt-auto">
                                <form action="{{ route('admin.reports.update', $bugReport) }}" method="POST" class="m-0 w-100">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $bugReport->status }}">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="admin_notes" class="form-control fs-13 bg-light border-0 shadow-none"
                                            placeholder="Add internal note..." value="{{ $bugReport->admin_notes }}">
                                        <button type="submit" class="btn btn-secondary px-3" title="Save Note">
                                            <iconify-icon icon="tabler:device-floppy"></iconify-icon>
                                        </button>
                                    </div>
                                </form>
                                <a href="{{ route('admin.reports.show', $bugReport) }}" class="btn btn-sm btn-light border shadow-sm w-100 d-flex align-items-center justify-content-center gap-2">
                                    <iconify-icon icon="tabler:eye"></iconify-icon> View Full Report
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body py-5 text-center d-flex flex-column align-items-center justify-content-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <iconify-icon icon="tabler:bug-off" class="text-muted" style="font-size: 40px;"></iconify-icon>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">No bug reports found</h5>
                                <p class="text-muted mb-0">You're all caught up! There are no bugs matching your criteria.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($bugReports->hasPages())
                <div class="mt-4 d-flex justify-content-center justify-content-md-end">
                    {{ $bugReports->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

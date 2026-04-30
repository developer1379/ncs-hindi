<x-app-layout title="Bug Reports | Fitx Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fs-18 fw-semibold m-0">Bug Reports</h4>
                    <p class="text-muted mb-0">Review website issues submitted by users.</p>
                </div>
            </div>

            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.reports.index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-medium">Search Report</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Search by title, description, URL, user name, or email"
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-medium">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>In Review</option>
                                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2 px-4">Filter</button>
                                <a href="{{ route('admin.reports.index') }}" class="btn btn-soft-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-centered mb-0 table-hover align-middle">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th class="ps-3">Issue</th>
                                    <th>Reported By</th>
                                    <th>Page</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th class="pe-3">Admin Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bugReports as $bugReport)
                                    <tr>
                                        <td class="ps-3" style="min-width: 280px;">
                                            <h5 class="mb-1 fs-15 fw-semibold">{{ $bugReport->title }}</h5>
                                            <p class="mb-0 text-muted">{{ \Illuminate\Support\Str::limit($bugReport->description, 120) }}</p>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $bugReport->user->name }}</div>
                                            <div class="text-muted fs-13">{{ $bugReport->user->email }}</div>
                                        </td>
                                        <td style="min-width: 220px;">
                                            @if ($bugReport->page_url)
                                                <a href="{{ $bugReport->page_url }}" target="_blank" class="text-primary">
                                                    {{ \Illuminate\Support\Str::limit($bugReport->page_url, 40) }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </td>
                                        <td style="min-width: 170px;">
                                            <form action="{{ route('admin.reports.update', $bugReport) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="open" {{ $bugReport->status === 'open' ? 'selected' : '' }}>Open</option>
                                                    <option value="in_review" {{ $bugReport->status === 'in_review' ? 'selected' : '' }}>In Review</option>
                                                    <option value="resolved" {{ $bugReport->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                </select>
                                                <input type="hidden" name="admin_notes" value="{{ $bugReport->admin_notes }}">
                                            </form>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $bugReport->created_at->format('d M Y') }}</span>
                                            <div class="text-muted fs-13">{{ $bugReport->created_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="pe-3" style="min-width: 320px;">
                                            <form action="{{ route('admin.reports.update', $bugReport) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $bugReport->status }}">
                                                <textarea name="admin_notes" rows="3" class="form-control form-control-sm mb-2"
                                                    placeholder="Add internal notes for this report...">{{ $bugReport->admin_notes }}</textarea>
                                                <button type="submit" class="btn btn-sm btn-soft-primary">Save Notes</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <iconify-icon icon="tabler:bug-off" class="fs-48 mb-2"></iconify-icon>
                                                <p class="fs-16 mb-0">No bug reports found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($bugReports->hasPages())
                        <div class="card-footer bg-white border-top">
                            {{ $bugReports->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

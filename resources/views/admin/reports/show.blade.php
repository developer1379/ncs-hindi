<x-app-layout title="View Bug Report | Fitx Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fs-18 fw-semibold m-0">{{ $bugReport->title }}</h4>
                    <p class="text-muted mb-0">Submitted by {{ $bugReport->user->name }} on {{ $bugReport->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-soft-secondary">
                    <iconify-icon icon="tabler:arrow-left" class="me-1"></iconify-icon> Back to Reports
                </a>
            </div>

            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 pb-0">
                            <h5 class="mb-0">Report Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="text-muted small mb-1">Page URL</div>
                                @if ($bugReport->page_url)
                                    <a href="{{ $bugReport->page_url }}" target="_blank">{{ $bugReport->page_url }}</a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </div>

                            <div class="border rounded p-3 report-content">
                                {!! $bugReport->description !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white border-0 pb-0">
                            <h5 class="mb-0">Reporter</h5>
                        </div>
                        <div class="card-body">
                            <div class="fw-semibold">{{ $bugReport->user->name }}</div>
                            <div class="text-muted">{{ $bugReport->user->email }}</div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 pb-0">
                            <h5 class="mb-0">Admin Update</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.reports.update', $bugReport) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="open" {{ $bugReport->status === 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_review" {{ $bugReport->status === 'in_review' ? 'selected' : '' }}>In Review</option>
                                        <option value="resolved" {{ $bugReport->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-medium">Admin Notes</label>
                                    <textarea name="admin_notes" rows="6" class="form-control"
                                        placeholder="Add internal notes for this report...">{{ $bugReport->admin_notes }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Save Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .report-content img {
                max-width: 100%;
                height: auto;
                border-radius: 12px;
                margin: 12px 0;
            }

            .report-content p:last-child {
                margin-bottom: 0;
            }
        </style>
    @endpush
</x-app-layout>








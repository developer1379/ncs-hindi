<x-app-layout title="Website Users | NCS Hindi Admin">
    <div class="content">
        <div class="container-fluid">
            <!-- Header Section -->
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Website Users List (Type 3)</h4>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ route('admin.website-users.index') }}" method="GET" id="websiteUserFilterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Search User</label>
                                <input type="text" name="search" class="form-control" placeholder="Name, Email or Phone..."
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Account Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="mdi mdi-filter-variant"></i> Filter
                                </button>
                                <a href="{{ route('admin.website-users.index') }}" class="btn btn-light">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- User Data Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table table-centered mb-0 table-nowrap table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>User Info</th>
                                            <th>Contact Info</th>
                                            <th class="text-center">Forum Threads</th>
                                            <th class="text-center">Replies Posted</th>
                                            <th class="text-center">Music Likes</th>
                                            <th class="text-center">FCM Push</th>
                                            <th>Status</th>
                                            <th>Joined Date</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @php
                                                            $avatarUrl = $user->profile_image ?:
                                                                'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=f59e0b&color=fff';
                                                        @endphp
                                                        <img src="{{ $avatarUrl }}"
                                                            class="rounded-circle avatar-sm me-3" alt="user-avatar"
                                                            style="width: 32px; height: 32px; object-fit: cover;">
                                                        <div>
                                                            <h5 class="m-0 font-13">{{ $user->name }}</h5>
                                                            <span class="badge bg-amber-500/10 text-amber-600 font-9 px-1.5 py-0.5">
                                                                Community Member
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="d-flex flex-column font-12">
                                                        <span><i class="mdi mdi-email-outline me-1 text-muted"></i>{{ $user->email }}</span>
                                                        @if ($user->phone)
                                                            <span class="mt-0.5"><i class="mdi mdi-phone-outline me-1 text-muted"></i>{{ $user->phone }}</span>
                                                        @else
                                                            <span class="mt-0.5 text-muted fs-11">-- No Phone --</span>
                                                        @endif
                                                    </div>
                                                </td>

                                                <td class="text-center font-semibold text-zinc-700">
                                                    <span class="badge bg-primary-subtle text-primary px-2 py-1 fs-11">
                                                        {{ number_format($user->threads_count) }}
                                                    </span>
                                                </td>

                                                <td class="text-center font-semibold text-zinc-700">
                                                    <span class="badge bg-success-subtle text-success px-2 py-1 fs-11">
                                                        {{ number_format($user->replies_count) }}
                                                    </span>
                                                </td>

                                                <td class="text-center font-semibold text-zinc-700">
                                                    <span class="badge bg-danger-subtle text-danger px-2 py-1 fs-11">
                                                        {{ number_format($user->interactions_count) }}
                                                    </span>
                                                </td>

                                                <td class="text-center">
                                                    @if ($user->fcm_tokens_count > 0)
                                                        <span class="badge bg-success-subtle text-success px-2.5 py-1 fs-11" title="Registered for Push Notifications">
                                                            <i class="mdi mdi-bell-ring-outline me-1"></i>Active
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light text-muted px-2.5 py-1 fs-11" title="No registered FCM tokens">
                                                            <i class="mdi mdi-bell-off-outline me-1"></i>Inactive
                                                        </span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <div class="form-check form-switch font-12">
                                                        <input class="form-check-input status-toggle" type="checkbox"
                                                            role="switch" id="status_{{ $user->id }}"
                                                            data-id="{{ $user->id }}"
                                                            {{ $user->status ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="status_{{ $user->id }}">
                                                            {{ $user->status ? 'Active' : 'Inactive' }}
                                                        </label>
                                                    </div>
                                                </td>

                                                <td class="font-12">
                                                    {{ $user->created_at->format('d M Y') }} <br>
                                                    <small class="text-muted">{{ $user->created_at->format('h:i A') }}</small>
                                                </td>

                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <!-- View Details Button -->
                                                        <a href="{{ route('admin.website-users.show', $user->id) }}"
                                                            class="btn btn-xs btn-soft-primary" title="View Activity Details">
                                                            <i class="mdi mdi-eye"></i> View Profile
                                                        </a>

                                                        <!-- Delete Button -->
                                                        <form action="{{ route('admin.website-users.destroy', $user->id) }}"
                                                            method="POST" class="delete-form d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this website user?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-soft-danger" title="Delete">
                                                                <i class="mdi mdi-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="mdi mdi-account-off fs-24 d-block mb-2"></i>
                                                        No website users found.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Handle Status Toggle via AJAX
            document.querySelectorAll('.status-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    let userId = this.getAttribute('data-id');
                    let status = this.checked ? 1 : 0;
                    let label = this.nextElementSibling;

                    // Update UI immediately (Optimistic UI)
                    label.textContent = status ? 'Active' : 'Inactive';

                    // Send AJAX request
                    fetch("{{ route('admin.website-users.update-status') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                id: userId,
                                status: status
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            toastr.success(data.message || 'Status updated successfully');
                        })
                        .catch(error => {
                            toastr.error('Failed to update status');
                            this.checked = !status;
                            label.textContent = !status ? 'Active' : 'Inactive';
                        });
                });
            });
        </script>
    @endpush
</x-app-layout>

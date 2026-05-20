<x-app-layout title="Website User Profile | NCS Hindi Admin">
    <div class="content">
        <div class="container-fluid">
            <!-- Header section -->
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Website User Profile</h4>
                </div>
                <div>
                    <a href="{{ route('admin.website-users.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="mdi mdi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>

            <!-- Profile Info Card -->
            <div class="row">
                <div class="col-lg-4">
                    <div class="card text-center py-4">
                        <div class="card-body">
                            @php
                                $avatarUrl =
                                    $user->profile_image ?:
                                    'https://ui-avatars.com/api/?name=' .
                                        urlencode($user->name) .
                                        '&background=f59e0b&color=fff';
                            @endphp
                            <img src="{{ $avatarUrl }}" class="rounded-circle avatar-xl img-thumbnail mb-3"
                                alt="profile-image" style="width: 120px; height: 120px; object-fit: cover;">

                            <h4 class="mb-1 fw-semibold">{{ $user->name }}</h4>
                            <p class="text-muted fs-13 mb-3">@NCS_Community_Member</p>

                            <div class="d-flex flex-column gap-2 text-start bg-light p-3 rounded-3">
                                <div>
                                    <span class="text-muted fs-11 uppercase d-block">Email Address</span>
                                    <span class="text-zinc-800 fw-semibold fs-13"><i
                                            class="mdi mdi-email-outline me-1"></i>{{ $user->email }}</span>
                                </div>
                                <hr class="my-1 border-zinc-200">
                                <div>
                                    <span class="text-muted fs-11 uppercase d-block">Phone Number</span>
                                    <span class="text-zinc-800 fw-semibold fs-13">
                                        <i class="mdi mdi-phone-outline me-1"></i>{{ $user->phone ?: '-- No Phone --' }}
                                    </span>
                                </div>
                                <hr class="my-1 border-zinc-200">
                                <div>
                                    <span class="text-muted fs-11 uppercase d-block">Joined Date</span>
                                    <span class="text-zinc-800 fw-semibold fs-13">
                                        <i
                                            class="mdi mdi-calendar-range me-1"></i>{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y, h:i A') }}
                                    </span>
                                </div>
                                <hr class="my-1 border-zinc-200">
                                <div>
                                    <span class="text-muted fs-11 uppercase d-block">Account Status</span>
                                    <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }} fs-11">
                                        {{ $user->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Panel (Activities) -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-bordered" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#threads" role="tab">
                                        <i class="mdi mdi-forum-outline me-1"></i> Forum Threads
                                        ({{ $user->threads->count() }})
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#replies" role="tab">
                                        <i class="mdi mdi-comment-multiple-outline me-1"></i> Comments/Replies
                                        ({{ $user->replies->count() }})
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#likes" role="tab">
                                        <i class="mdi mdi-music-note me-1"></i> Music Likes
                                        ({{ $user->interactions->where('type', 'like')->count() }})
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab content -->
                            <div class="tab-content pt-3">
                                <!-- Forum Threads Tab -->
                                <div class="tab-pane active" id="threads" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-centered mb-0 table-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Thread Title</th>
                                                    <th>Category</th>
                                                    <th>Created At</th>
                                                    <th>Replies</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($user->threads as $thread)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('webapp.forum.show', $thread->slug) }}"
                                                                target="_blank" class="fw-semibold text-primary">
                                                                {{ Str::limit($thread->title, 40) }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-zinc-100 text-zinc-800">
                                                                {{ $thread->category->name ?? 'General' }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $thread->created_at->format('d M Y') }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-light text-muted">{{ $thread->replies->count() }}</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-4 text-muted">
                                                            No forum threads created by this user yet.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Replies Tab -->
                                <div class="tab-pane" id="replies" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-centered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Comment Snippet</th>
                                                    <th>On Thread</th>
                                                    <th>Date Posted</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($user->replies as $reply)
                                                    <tr>
                                                        <td style="max-width: 250px;">
                                                            <span class="text-muted text-wrap d-block">
                                                                {{ strip_tags($reply->content) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if ($reply->thread)
                                                                <a href="{{ route('webapp.forum.show', $reply->thread->slug) }}"
                                                                    target="_blank" class="text-primary font-semibold">
                                                                    {{ Str::limit($reply->thread->title, 35) }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Deleted Thread</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $reply->created_at->format('d M Y, h:i A') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4 text-muted">
                                                            No replies or comments posted yet.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Likes / Interactions Tab -->
                                <div class="tab-pane" id="likes" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-centered mb-0 table-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Music Track</th>
                                                    <th>Artist Name</th>
                                                    <th>Type</th>
                                                    <th>Date Logged</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($user->interactions as $interaction)
                                                    @if ($interaction->music)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div
                                                                        class="bg-amber-500/10 p-1.5 rounded-lg me-2 text-amber-600">
                                                                        <i class="mdi mdi-music-note fs-16"></i>
                                                                    </div>
                                                                    <a href="{{ route('webapp.music.show', $interaction->music->id) }}"
                                                                        target="_blank"
                                                                        class="fw-semibold text-primary">
                                                                        {{ $interaction->music->title }}
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td>{{ $interaction->music->artist_name ?: 'NCS Artist' }}
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-danger-subtle text-danger text-uppercase px-2 py-1 fs-10 font-bold">
                                                                    <i
                                                                        class="mdi mdi-heart me-1"></i>{{ $interaction->type }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $interaction->created_at->format('d M Y, h:i A') }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-4 text-muted">
                                                            No music tracks liked or rated by this user.
                                                        </td>
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
        </div>
    </div>
</x-app-layout>

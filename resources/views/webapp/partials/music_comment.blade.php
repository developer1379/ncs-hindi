@php
    $reactionTypes = ['like', 'love', 'haha', 'wow', 'sad', 'angry'];
    $emojiMap = ['like'=>'👍','love'=>'❤️','haha'=>'😂','wow'=>'😮','sad'=>'😢','angry'=>'😡'];
    $myReaction = $userReactions[$comment->id] ?? null;
    $counts = $comment->reaction_counts;
    $hasReactions = array_sum($counts) > 0;
@endphp

<div class="comment-card bg-zinc-900/40 border border-zinc-800 rounded-2xl p-5 mb-4" data-comment-id="{{ $comment->id }}">
    <div class="flex gap-3">
        {{-- Avatar --}}
        <img src="{{ $comment->avatar }}" alt="{{ $comment->display_name }}"
             class="w-10 h-10 rounded-xl object-cover border border-zinc-700 shrink-0">

        <div class="flex-1 min-w-0">
            {{-- Meta --}}
            <div class="flex items-center gap-2 mb-2 flex-wrap">
                <span class="text-sm font-bold text-white">{{ $comment->display_name }}</span>
                @if($comment->user_id)
                    <span class="px-2 py-0.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[9px] font-black rounded-full uppercase tracking-wider">Member</span>
                @else
                    <span class="px-2 py-0.5 bg-zinc-800 border border-zinc-700 text-zinc-500 text-[9px] font-black rounded-full uppercase tracking-wider">Guest</span>
                @endif
                <span class="text-zinc-600 text-xs">{{ $comment->created_at->diffForHumans() }}</span>
            </div>

            {{-- Content --}}
            <div class="quill-content text-zinc-300 text-sm leading-relaxed mb-4 prose-sm max-w-none">
                {!! $comment->comment !!}
            </div>

            {{-- Actions Row --}}
            <div class="flex items-center gap-3 flex-wrap">
                {{-- Emoji Reaction Trigger --}}
                <div class="reaction-wrapper relative">
                    <button class="reaction-trigger text-xs text-zinc-500 hover:text-amber-400 font-bold flex items-center gap-1.5 transition">
                        @if($myReaction)
                            <span>{{ $emojiMap[$myReaction] }}</span>
                        @else
                            <i class="fa-regular fa-face-smile text-amber-500/60"></i>
                        @endif
                        <span>{{ $myReaction ? ucfirst($myReaction) : 'React' }}</span>
                    </button>
                    {{-- Float Bar --}}
                    <div class="reaction-bar">
                        @foreach($reactionTypes as $type)
                            <button class="reaction-emoji" data-type="{{ $type }}"
                                title="{{ ucfirst($type) }}" aria-label="{{ $type }}">
                                {{ $emojiMap[$type] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Reply Button --}}
                <button class="reply-toggle-btn text-xs text-zinc-500 hover:text-amber-400 font-bold flex items-center gap-1.5 transition"
                        data-comment-id="{{ $comment->id }}">
                    <i class="fa-solid fa-reply text-amber-500/60 text-[10px]"></i> Reply
                </button>

                {{-- Reaction Count Badges --}}
                <div class="reaction-counts-row flex gap-1.5 flex-wrap">
                    @foreach($reactionTypes as $type)
                        @if(($counts[$type] ?? 0) > 0)
                            <button class="reaction-count-badge {{ $myReaction === $type ? 'active' : '' }}" data-type="{{ $type }}">
                                {{ $emojiMap[$type] }} {{ $counts[$type] }}
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Reply Form Placeholder --}}
            <div class="reply-form-container mt-3" style="display:none"></div>

            {{-- Existing Replies --}}
            @if($comment->replies->isNotEmpty())
                <div class="replies-container mt-5 space-y-3 pl-0.5">
                    @foreach($comment->replies as $reply)
                        @php
                            $myReaction = $userReactions[$reply->id] ?? null;
                            $counts     = $reply->reaction_counts;
                        @endphp
                        <div class="reply-card comment-card bg-zinc-900/60 border border-zinc-800/60 rounded-xl p-4"
                             data-comment-id="{{ $reply->id }}">
                            <div class="flex gap-3">
                                <img src="{{ $reply->avatar }}" alt="{{ $reply->display_name }}"
                                     class="w-8 h-8 rounded-lg object-cover border border-zinc-700 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                        <span class="text-xs font-bold text-white">{{ $reply->display_name }}</span>
                                        @if($reply->user_id)
                                            <span class="px-2 py-0.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[8px] font-black rounded-full uppercase tracking-wider">Member</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-zinc-800 border border-zinc-700 text-zinc-500 text-[8px] font-black rounded-full uppercase tracking-wider">Guest</span>
                                        @endif
                                        <span class="text-zinc-600 text-[11px]">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="quill-content text-zinc-400 text-[13px] leading-relaxed mb-3">
                                        {!! $reply->comment !!}
                                    </div>
                                    {{-- Reply Reactions --}}
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <div class="reaction-wrapper relative">
                                            <button class="reaction-trigger text-[11px] text-zinc-500 hover:text-amber-400 font-bold flex items-center gap-1 transition">
                                                @if($myReaction)
                                                    <span>{{ $emojiMap[$myReaction] }}</span>
                                                @else
                                                    <i class="fa-regular fa-face-smile text-amber-500/60 text-[10px]"></i>
                                                @endif
                                                <span>{{ $myReaction ? ucfirst($myReaction) : 'React' }}</span>
                                            </button>
                                            <div class="reaction-bar">
                                                @foreach($reactionTypes as $type)
                                                    <button class="reaction-emoji" data-type="{{ $type }}" title="{{ ucfirst($type) }}">{{ $emojiMap[$type] }}</button>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="reaction-counts-row flex gap-1 flex-wrap">
                                            @foreach($reactionTypes as $type)
                                                @if(($counts[$type] ?? 0) > 0)
                                                    <button class="reaction-count-badge {{ $myReaction === $type ? 'active' : '' }}" data-type="{{ $type }}">
                                                        {{ $emojiMap[$type] }} {{ $counts[$type] }}
                                                    </button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty replies container for JS to inject into --}}
                <div class="replies-container mt-4 space-y-3"></div>
            @endif
        </div>
    </div>
</div>

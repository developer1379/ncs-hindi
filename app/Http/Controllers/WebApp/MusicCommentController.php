<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use App\Models\Music;
use App\Models\MusicComment;
use App\Models\MusicCommentReaction;
use App\Services\ImgBBService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MusicCommentController extends Controller
{
    /**
     * Store a new comment (top-level or reply).
     */
    public function store(Request $request, $musicId)
    {
        $music = Music::findOrFail($musicId);

        $rules = [
            'comment'   => 'required|string|max:50000',
            'parent_id' => 'nullable|exists:music_comments,id',
        ];

        // Guest users must supply a name and email
        if (!Auth::check()) {
            $rules['name']  = 'required|string|max:100';
            $rules['email'] = 'required|email|max:150';
        }

        $validated = $request->validate($rules);

        $comment = MusicComment::create([
            'music_id'   => $music->id,
            'user_id'    => Auth::id(),
            'parent_id'  => $validated['parent_id'] ?? null,
            'name'       => $validated['name'] ?? null,
            'email'      => $validated['email'] ?? null,
            'comment'    => $validated['comment'],
            'status'     => 'approved',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Load the comment's user relationship so we can render it
        $comment->load(['user', 'reactions']);

        return response()->json([
            'success'  => true,
            'message'  => 'Comment posted!',
            'comment'  => $this->buildCommentData($comment),
        ]);
    }

    /**
     * Toggle/update an emoji reaction on a comment.
     */
    public function react(Request $request, $commentId)
    {
        $validated = $request->validate([
            'type' => 'required|in:like,love,haha,wow,sad,angry',
        ]);

        $comment = MusicComment::findOrFail($commentId);
        $ip = $request->ip();
        $userId = Auth::id();
        $type = $validated['type'];

        // Find existing reaction by user (logged in) or IP (guest)
        $query = MusicCommentReaction::where('music_comment_id', $comment->id);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->whereNull('user_id')->where('ip_address', $ip);
        }
        $existing = $query->first();

        if ($existing) {
            if ($existing->type === $type) {
                // Toggle off: remove reaction
                $existing->delete();
                $userReaction = null;
            } else {
                // Switch reaction type
                $existing->update(['type' => $type]);
                $userReaction = $type;
            }
        } else {
            // Create new reaction
            MusicCommentReaction::create([
                'music_comment_id' => $comment->id,
                'user_id'          => $userId,
                'ip_address'       => $ip,
                'type'             => $type,
            ]);
            $userReaction = $type;
        }

        // Return fresh counts
        $comment->load('reactions');
        $counts = $comment->reaction_counts;
        $total = array_sum($counts);

        return response()->json([
            'success'       => true,
            'user_reaction' => $userReaction,
            'counts'        => $counts,
            'total'         => $total,
        ]);
    }

    /**
     * Upload an image from the Quill editor to ImgBB.
     */
    public function uploadImage(Request $request, ImgBBService $imgBB)
    {
        $request->validate([
            'file' => 'required|image|max:10240',
        ]);

        $url = $imgBB->upload($request->file('file'));

        if ($url) {
            return response()->json(['location' => $url]);
        }

        return response()->json(['error' => 'Image upload failed.'], 400);
    }

    /**
     * Build a serialisable data array for a comment (used in JSON responses).
     */
    private function buildCommentData(MusicComment $comment): array
    {
        return [
            'id'             => $comment->id,
            'comment'        => $comment->comment,
            'display_name'   => $comment->display_name,
            'avatar'         => $comment->avatar,
            'is_auth'        => (bool) $comment->user_id,
            'parent_id'      => $comment->parent_id,
            'created_at_human' => $comment->created_at->diffForHumans(),
            'reaction_counts'  => $comment->reaction_counts,
            'user_reaction'    => null,
        ];
    }
}

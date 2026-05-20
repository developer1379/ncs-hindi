<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MusicComment extends Model
{
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'music_id',
        'user_id',
        'parent_id',
        'name',
        'email',
        'comment',
        'status',
        'ip_address',
        'user_agent',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // ---- Relationships ----

    public function music()
    {
        return $this->belongsTo(Music::class, 'music_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(MusicComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(MusicComment::class, 'parent_id')
            ->where('status', 'approved')
            ->with(['user', 'reactions'])
            ->orderBy('created_at', 'asc');
    }

    public function reactions()
    {
        return $this->hasMany(MusicCommentReaction::class, 'music_comment_id');
    }

    // ---- Helpers ----

    /**
     * Get display name (authenticated user name or guest name).
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->user?->name ?? $this->name ?? 'Anonymous';
    }

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarAttribute(): string
    {
        $name = urlencode($this->display_name);
        if ($this->user && $this->user->profile_image && strlen($this->user->profile_image) > 20) {
            return $this->user->profile_image;
        }
        return "https://ui-avatars.com/api/?name={$name}&background=18181b&color=f59e0b&bold=true&size=64";
    }

    /**
     * Get summarised reaction counts for all 6 types.
     */
    public function getReactionCountsAttribute(): array
    {
        $types = ['like', 'love', 'haha', 'wow', 'sad', 'angry'];
        $counts = [];
        $reactionsCollection = $this->reactions ?? collect();
        foreach ($types as $type) {
            $counts[$type] = $reactionsCollection->where('type', $type)->count();
        }
        return $counts;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MusicCommentReaction extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'music_comment_id',
        'user_id',
        'ip_address',
        'type',
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

    public function comment()
    {
        return $this->belongsTo(MusicComment::class, 'music_comment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

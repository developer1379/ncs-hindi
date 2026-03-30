<?php

namespace App\Notifications;

use App\Models\MusicStem;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MusicPublishedNotification extends Notification
{
    use Queueable;

    public function __construct(protected MusicStem $stem) {}

    public function via($notifiable): array
    {
        return ['fcm'];
    }

    public function toFCM(): array
    {
        return [
            'title' => 'New music release',
            'body' => trim(($this->stem->title ?? 'A new track') . ' is now available.'),
        ];
    }

    public function toData(): array
    {
        return [
            'type' => 'music_release',
            'stem_id' => $this->stem->id,
            'slug' => $this->stem->slug,
            'title' => $this->stem->title,
            'artist_name' => $this->stem->artist_name,
            'url' => route('webapp.stems.show', ['slug' => $this->stem->slug]),
        ];
    }
}

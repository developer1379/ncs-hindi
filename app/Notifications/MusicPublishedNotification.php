<?php

namespace App\Notifications;

use App\Models\Music;
use Illuminate$musicus\Queueable;
use Illuminate\Notifications\Notification;

class MusicPublishedNotification extends Notification
{
    use Queueable;

    public function __construct(protected Music $music) {}

    public function via($notifiable): array
    {
        return ['fcm'];
    }

    public function toFCM(): array
    {
        return [
            'title' => 'New music release',
            'body' => trim(($this->music->title ?? 'A new track') . ' is now available.'),
        ];
    }

    public function toData(): array
    {
        return [
            'type' => 'music_release',
            'stem_id' => $this->music->id,
            'slug' => $this->music->slug,
            'title' => $this->music->title,
            'artist_name' => $this->music->artist_name,
            'url' => route('webapp.music.show', ['slug' => $this->music->slug]),
        ];
    }
}








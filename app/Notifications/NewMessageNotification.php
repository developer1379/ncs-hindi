<?php

namespace App\Notifications;

use Illuminate$musicus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(protected $messageData) {}

    public function via($notifiable): array
    {
        $channels = ['database'];

        if (!empty($notifiable->routeNotificationForFCM())) {
            $channels[] = 'fcm';
        }

        return $channels;
    }

    public function toArray($notifiable): array
    {
        return [
            'seeker_id' => $this->messageData->seeker_id,
            'coach_id'  => $this->messageData->coach_id,
            'message'   => $this->messageData->message,
            'type'      => 'message'
        ];
    }

    public function toFCM(): array
    {
        $title = $this->messageData->title ?? 'New Message';
        $body = $this->messageData->message ?? 'You have a new message.';

        return [
            'title' => $title,
            'body' => $body,
        ];
    }

    public function toData(): array
    {
        return [
            'type' => 'message',
            'seeker_id' => $this->messageData->seeker_id ?? null,
            'coach_id' => $this->messageData->coach_id ?? null,
        ];
    }
}








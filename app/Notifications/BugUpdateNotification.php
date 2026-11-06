<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BugUpdateNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly int $projectId,
        private readonly int $bugId,
        private readonly string $event,
        private readonly string $title,
        private readonly string $message,
        private readonly string $actorName,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'project_id' => $this->projectId,
            'bug_id' => $this->bugId,
            'event' => $this->event,
            'title' => $this->title,
            'message' => $this->message,
            'actor_name' => $this->actorName,
        ];
    }
}

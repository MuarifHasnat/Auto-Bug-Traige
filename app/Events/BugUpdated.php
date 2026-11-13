<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BugUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $projectId,
        public int $bugId,
        public string $eventType,
        public string $summary,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('project.'.$this->projectId)];
    }

    public function broadcastAs(): string
    {
        return 'bug.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'project_id' => $this->projectId,
            'bug_id' => $this->bugId,
            'event_type' => $this->eventType,
            'summary' => $this->summary,
            'occurred_at' => now()->toIso8601String(),
        ];
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AgentProgressUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param array<string, mixed> $summary
     */
    public function __construct(
        public int $projectId,
        public string $runId,
        public string $step,
        public string $label,
        public string $status,
        public string $message,
        public array $summary = [],
        public ?string $timestamp = null,
    ) {
        $this->timestamp = $timestamp ?: now()->toIso8601String();
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('project.'.$this->projectId)];
    }

    public function broadcastAs(): string
    {
        return 'agent.progress';
    }

    public function broadcastWith(): array
    {
        return [
            'project_id' => $this->projectId,
            'run_id' => $this->runId,
            'step' => $this->step,
            'label' => $this->label,
            'status' => $this->status,
            'message' => $this->message,
            'summary' => $this->summary,
            'timestamp' => $this->timestamp,
        ];
    }
}


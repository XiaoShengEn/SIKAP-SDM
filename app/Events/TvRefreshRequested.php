<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Broadcast a lightweight signal to tell TV clients to refresh their data.
 * We keep it generic so every CRUD action can reuse it.
 */
class TvRefreshRequested implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $section;
    public string $action;
    public ?int $id;

    public function __construct(string $section, string $action = 'changed', ?int $id = null)
    {
        $this->section = $section;
        $this->action = $action;
        $this->id = $id;
    }

    public function broadcastOn(): Channel
    {
        // Public channel - TV is public screen.
        return new Channel('tv-updates');
    }

    public function broadcastAs(): string
    {
        return 'TvRefreshRequested';
    }

    public function broadcastWith(): array
    {
        return [
            'section' => $this->section,
            'action' => $this->action,
            'id' => $this->id,
            'ts' => now()->timestamp,
        ];
    }
}


<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event untuk broadcast agenda updates via Reverb
 */
class AgendaUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $kegiatan;

    /**
     * Create a new event instance.
     */
    public function __construct($kegiatan = null)
    {
        $this->kegiatan = $kegiatan;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        // Public channel - semua user bisa listen
        return new Channel('agenda-updates');
    }

    /**
     * Data yang akan dikirim ke client
     */
public function broadcastWith(): array
{
    return [
        'id' => $this->kegiatan?->kegiatan_id,
        'tanggal' => $this->kegiatan?->tanggal_kegiatan,
    ];
}

    /**
     * Nama event yang akan di-listen di frontend
     */
    public function broadcastAs(): string
    {
        return 'AgendaUpdated';
    }
}
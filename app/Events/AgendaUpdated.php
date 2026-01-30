<?php

namespace App\Events;

use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AgendaUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $agenda;

    public function __construct(Kegiatan $agenda)
    {
        $tanggal = Carbon::parse($agenda->tanggal_kegiatan)->locale('id');

        $this->agenda = [
            'kegiatan_id' => $agenda->kegiatan_id,

            // 🔥 untuk tampilan
            'tanggal_label' => $tanggal->translatedFormat('l, d F Y'),
            'jam_label' => $agenda->jam
                ? Carbon::parse($agenda->jam)->format('H:i') . ' WIB'
                : '-',

            // 🔥 data asli
            'tanggal_kegiatan' => $agenda->tanggal_kegiatan,
            'jam' => $agenda->jam,

            'nama_kegiatan' => $agenda->nama_kegiatan,
            'tempat' => $agenda->tempat,
            'disposisi' => $agenda->disposisi,
            'keterangan' => $agenda->keterangan,
        ];
    }

    public function broadcastOn(): Channel
    {
        return new Channel('agenda-channel');
    }

    public function broadcastAs(): string
    {
        return 'agenda.updated';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'tb_kegiatan';
    protected $primaryKey = 'kegiatan_id';
    public $timestamps = false;

    protected $fillable = [
        'tanggal_kegiatan',
        'jam',
        'nama_kegiatan',
        'tempat',
        'disposisi',
        'keterangan',
    ];

    // ✅ HARUS DI DALAM CLASS
    public function scopeAgendaOrder($query)
    {
        $today = Carbon::today()->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();

        return $query->orderByRaw("
            CASE
                WHEN tanggal_kegiatan = ? THEN 0
                WHEN tanggal_kegiatan = ? THEN 1
                WHEN tanggal_kegiatan > ? THEN 2
                ELSE 3
            END
        ", [$today, $tomorrow, $tomorrow])
        ->orderBy('tanggal_kegiatan', 'asc')
        ->orderBy('jam', 'asc');
    }
}

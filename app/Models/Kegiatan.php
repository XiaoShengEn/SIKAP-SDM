<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'tb_kegiatan';
    protected $primaryKey = 'kegiatan_id';

    public $timestamps = false;

    protected $fillable = [
        'tanggal_kegiatan',
        'nama_kegiatan',
        'disposisi',
        'keterangan',
        'tempat'
    ];
}

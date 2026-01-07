<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pimpinan extends Model
{
    protected $table = 'tb_profil';
    protected $primaryKey = 'id_profil';
    public $timestamps = false;

    protected $fillable = [
        'nama_pimpinan',
        'jabatan_pimpinan',
        'foto_pimpinan'
    ];
}

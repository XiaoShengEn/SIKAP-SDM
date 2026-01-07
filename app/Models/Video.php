<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'tb_video';
    protected $primaryKey = 'video_id';
    public $timestamps = false;

    protected $fillable = [
        'video_kegiatan',
        'video_keterangan'
    ];
}

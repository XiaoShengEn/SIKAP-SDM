<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RunningText extends Model
{
    protected $table = 'tb_runningtext';
    protected $primaryKey = 'id_text';
    public $timestamps = false;

    protected $fillable = ['isi_text'];
}

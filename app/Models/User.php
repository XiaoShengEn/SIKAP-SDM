<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // TABEL KUSTOM
    protected $table = 'tb_admin';

    // PRIMARY KEY KUSTOM
    protected $primaryKey = 'id_admin';

    // TABEL LU GA PUNYA created_at / updated_at
    public $timestamps = false;

    protected $fillable = [
        'nama_admin',
        'email_admin',
        'password_admin',
        'role_admin',
    ];

    protected $hidden = [
        'password_admin',
    ];

    // Biar auth Laravel pake kolom yg bener
    public function getAuthPassword()
    {
        return $this->password_admin;
    }
}

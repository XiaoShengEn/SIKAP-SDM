<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tb_profil')) {
            Schema::create('tb_profil', function (Blueprint $table) {
                $table->id('id_profil');
                $table->string('nama_pimpinan', 100);
                $table->string('jabatan_pimpinan', 100);
                $table->string('foto_pimpinan', 256);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_profil');
    }
};

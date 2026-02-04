<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_profil', function (Blueprint $table) {
            $table->bigIncrements('id_profil');
            $table->string('nama_pimpinan', 100);
            $table->string('jabatan_pimpinan', 100);
            $table->string('foto_pimpinan', 256)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_profil');
    }
};

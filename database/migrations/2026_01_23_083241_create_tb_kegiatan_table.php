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
        Schema::create('tb_kegiatan', function (Blueprint $table) {
            $table->bigIncrements('kegiatan_id');
            $table->date('tanggal_kegiatan');
            $table->time('jam')->nullable();
            $table->string('nama_kegiatan', 255);
            $table->string('tempat', 255)->nullable();
            $table->string('disposisi', 255)->nullable();
            $table->text('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_kegiatan');
    }
};

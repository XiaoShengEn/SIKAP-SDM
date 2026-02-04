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
        Schema::create('tb_admin', function (Blueprint $table) {
            $table->bigIncrements('id_admin');
            $table->string('nama_admin', 50)->unique();
            $table->string('nip', 18)->unique();
            $table->string('password_admin');
            $table->string('bagian', 50);
            $table->string('role_admin', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_admin');
    }
};

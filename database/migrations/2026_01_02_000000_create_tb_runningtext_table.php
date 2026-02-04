<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_runningtext', function (Blueprint $table) {
            $table->bigIncrements('id_text');
            $table->string('isi_text', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_runningtext');
    }
};


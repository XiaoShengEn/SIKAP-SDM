<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_kegiatan', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_kegiatan', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('keterangan');
            }

            if (!Schema::hasColumn('tb_kegiatan', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });

        $now = now();

        DB::table('tb_kegiatan')
            ->whereNull('created_at')
            ->update(['created_at' => $now]);

        DB::table('tb_kegiatan')
            ->whereNull('updated_at')
            ->update(['updated_at' => $now]);
    }

    public function down(): void
    {
        Schema::table('tb_kegiatan', function (Blueprint $table) {
            if (Schema::hasColumn('tb_kegiatan', 'updated_at')) {
                $table->dropColumn('updated_at');
            }

            if (Schema::hasColumn('tb_kegiatan', 'created_at')) {
                $table->dropColumn('created_at');
            }
        });
    }
};

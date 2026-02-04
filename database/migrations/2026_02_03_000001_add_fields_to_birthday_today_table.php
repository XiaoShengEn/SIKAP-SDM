<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('birthday_today')) {
            return;
        }

        Schema::table('birthday_today', function (Blueprint $table) {
            if (!Schema::hasColumn('birthday_today', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('nama');
            }

            if (!Schema::hasColumn('birthday_today', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (!Schema::hasColumn('birthday_today', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('birthday_today')) {
            return;
        }

        Schema::table('birthday_today', function (Blueprint $table) {
            if (Schema::hasColumn('birthday_today', 'tanggal')) {
                $table->dropColumn('tanggal');
            }
            if (Schema::hasColumn('birthday_today', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('birthday_today', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }
};


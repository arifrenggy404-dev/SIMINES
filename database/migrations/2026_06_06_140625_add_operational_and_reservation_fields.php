<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan_ongkir', function (Blueprint $table) {
            $table->time('jam_buka')->default('08:00');
            $table->time('jam_tutup')->default('20:00');
            $table->boolean('manual_tutup')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan_ongkir', function (Blueprint $table) {
            $table->dropColumn(['jam_buka', 'jam_tutup', 'manual_tutup']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->boolean('is_reservasi')->default(false)->after('metode_pembayaran');
            $table->dateTime('jadwal_pengambilan')->nullable()->after('is_reservasi');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['is_reservasi', 'jadwal_pengambilan']);
        });
    }
};

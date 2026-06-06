<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Status untuk pelacakan proses (Pending, Dibayar, Diproses, Dikirim, Selesai)
            // Kolom 'status' yang sudah ada akan tetap digunakan sebagai status pembayaran/utama
            // Namun kita akan memperluas fungsionalitasnya atau menambahkan keterangan waktu
            $table->timestamp('diproses_at')->nullable();
            $table->timestamp('dikirim_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['diproses_at', 'dikirim_at', 'selesai_at']);
        });
    }
};

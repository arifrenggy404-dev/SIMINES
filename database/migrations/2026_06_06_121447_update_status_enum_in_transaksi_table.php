<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Menggunakan raw SQL untuk memperbarui ENUM karena 'Dikirim' belum terdaftar di database
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN status ENUM('Pending', 'Paid', 'Diproses', 'Dikirim', 'Selesai', 'Failed') DEFAULT 'Pending'");
    }

    public function down(): void
    {
        // Kembalikan ke enum semula (perlu hati-hati jika ada data 'Dikirim' yang sudah masuk)
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN status ENUM('Pending', 'Paid', 'Diproses', 'Selesai', 'Failed') DEFAULT 'Pending'");
    }
};

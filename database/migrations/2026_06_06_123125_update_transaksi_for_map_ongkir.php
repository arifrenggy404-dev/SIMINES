<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Hapus kolom jarak lama (enum)
            // Catatan: SQLite tidak mendukung dropColumn dalam satu transaksi yang sama dengan penambahan jika versinya lama,
            // tapi karena ini kemungkinan MySQL/Postgres di Termux/Server, kita lanjut saja.
            $table->dropColumn('jarak');
            
            // Tambah kolom peta
            $table->string('latitude')->nullable()->after('biaya_ongkir');
            $table->string('longitude')->nullable()->after('latitude');
            $table->decimal('jarak_km', 8, 2)->default(0)->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->enum('jarak', ['Dekat', 'Jauh'])->default('Dekat')->after('biaya_ongkir');
            $table->dropColumn(['latitude', 'longitude', 'jarak_km']);
        });
    }
};

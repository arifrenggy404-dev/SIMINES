<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('kode_voucher')->nullable()->after('biaya_ongkir');
            $table->integer('potongan_voucher')->default(0)->after('kode_voucher');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['kode_voucher', 'potongan_voucher']);
        });
    }
};

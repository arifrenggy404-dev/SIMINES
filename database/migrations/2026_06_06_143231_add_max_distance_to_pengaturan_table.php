<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan_ongkir', function (Blueprint $table) {
            $table->integer('max_jarak_km')->default(10)->after('gratis_ongkir_km');
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan_ongkir', function (Blueprint $table) {
            $table->dropColumn('max_jarak_km');
        });
    }
};

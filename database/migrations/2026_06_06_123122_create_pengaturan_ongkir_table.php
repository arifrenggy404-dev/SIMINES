<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_ongkir', function (Blueprint $table) {
            $table->id();
            $table->string('toko_latitude')->default('-9.6591'); // Default Waingapu
            $table->string('toko_longitude')->default('120.2633');
            $table->integer('harga_per_km')->default(2000);
            $table->integer('gratis_ongkir_km')->default(1);
            $table->timestamps();
        });

        // Insert default data
        DB::table('pengaturan_ongkir')->insert([
            'toko_latitude' => '-9.6591',
            'toko_longitude' => '120.2633',
            'harga_per_km' => 2000,
            'gratis_ongkir_km' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_ongkir');
    }
};

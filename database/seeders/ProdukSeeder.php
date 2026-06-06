<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_produk' => 'Es Campur', 'harga' => 5000, 'stok' => 50],
            ['nama_produk' => 'Es Cendol', 'harga' => 5000, 'stok' => 50],
            ['nama_produk' => 'Es Teler', 'harga' => 5000, 'stok' => 50],
            ['nama_produk' => 'Es Kelapa', 'harga' => 5000, 'stok' => 50],
            ['nama_produk' => 'Es Alpukat', 'harga' => 10000, 'stok' => 30],
            ['nama_produk' => 'Es Cincau', 'harga' => 10000, 'stok' => 30],
            ['nama_produk' => 'Es Buah', 'harga' => 5000, 'stok' => 50],
            ['nama_produk' => 'Es Jeruk', 'harga' => 5000, 'stok' => 50],
            ['nama_produk' => 'Es Teh Manis', 'harga' => 5000, 'stok' => 100],
            ['nama_produk' => 'Es Susu Cokelat', 'harga' => 10000, 'stok' => 30],
        ];

        foreach ($data as $item) {
            Produk::create($item);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        Voucher::create([
            'kode' => 'SEGAR10',
            'potongan_harga' => 10000,
            'minimal_belanja' => 20000
        ]);
        Voucher::create([
            'kode' => 'PROMO5',
            'potongan_harga' => 5000,
            'minimal_belanja' => 0
        ]);
    }
}

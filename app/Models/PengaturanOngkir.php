<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanOngkir extends Model
{
    protected $table = 'pengaturan_ongkir';
    protected $fillable = [
        'toko_latitude',
        'toko_longitude',
        'harga_per_km',
        'gratis_ongkir_km',
        'jam_buka',
        'jam_tutup',
        'manual_tutup',
    ];

    /**
     * Mengecek apakah toko sedang buka saat ini
     */
    public function isOpen()
    {
        if ($this->manual_tutup) return false;

        $sekarang = now()->format('H:i:s');
        return ($sekarang >= $this->jam_buka && $sekarang <= $this->jam_tutup);
    }
}

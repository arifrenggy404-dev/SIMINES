<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_user',
        'tanggal',
        'total_harga',
        'latitude',
        'longitude',
        'jarak_km',
        'biaya_ongkir',
        'kode_voucher',
        'potongan_voucher',
        'status',
        'metode_pembayaran',
        'alamat_pengiriman',
        'is_reservasi',
        'jadwal_pengambilan',
        'diproses_at',
        'dikirim_at',
        'selesai_at',
    ];

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke DetailTransaksi
     */
    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $primaryKey = 'id_voucher';
    protected $fillable = ['kode', 'potongan_harga', 'minimal_belanja'];
}

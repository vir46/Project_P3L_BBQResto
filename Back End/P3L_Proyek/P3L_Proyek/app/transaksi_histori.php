<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class transaksi_histori extends Model
{
    protected $fillable = [
        'id_histori', 'jenis_perubahan_stock', 'tanggal_stock_berubah',
        'jumlah_stock_perubahan', 'id_bahan', 'id_karyawan'
    ];

    protected $primaryKey = 'id_histori';
    public $timestamps = false;
    protected $table = 'transaksi_histori';
}

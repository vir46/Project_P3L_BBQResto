<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class transaksi_pengadaan extends Model
{
    protected $fillable = [
        'id_stock_in', 'tanggal_stock_in', 'total_harga_stock', 'jumlah_stock_transaksi', 
        'harga_satuan', 'id_bahan', 'id_karyawan'
    ];

    protected $primaryKey = 'id_stock_in';
    public $timestamps = false;
    protected $table = 'transaksi_pengadaan';
}

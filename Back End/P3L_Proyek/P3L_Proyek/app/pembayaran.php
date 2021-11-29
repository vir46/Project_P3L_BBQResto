<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    protected $fillable = [
        'id_pembayaran', 'status_pembayaran', 'total_pembayaran', 
        'total_kembalian', 'jenis_pembayaran', 'tanggal_bayar', 'id_order', 'id_info', 
        'Verif_Code'
    ];

    protected $primaryKey = 'id_pembayaran';
    public $timestamps = false;
    protected $attributes = [
        'status_pembayaran' => 'Paid',
     ];
    protected $table = 'pembayaran';
}

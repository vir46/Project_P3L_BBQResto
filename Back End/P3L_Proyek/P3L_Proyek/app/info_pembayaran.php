<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class info_pembayaran extends Model
{
    protected $fillable = [
        'id_info', 'jenis_kartu', 'merk_kartu', 'exp_date_kartu', 
        'nomor_kartu', 'Card_Holder'
    ];

    protected $primaryKey = 'id_info';
    public $timestamps = false;
    protected $table = 'info_pembayaran';
}

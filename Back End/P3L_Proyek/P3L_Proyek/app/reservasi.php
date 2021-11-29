<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class reservasi extends Model
{
    protected $fillable = [
        'id_reservasi', 'tanggal_pencatatan_reservasi', 'jam_pencatatan_reservasi',
        'tanggal_reservasi', 'sesi_reservasi', 'id_customer', 'id_meja', 
        'id_karyawan', 'status_reservasi'
    ];

    protected $primaryKey = 'id_reservasi';
    public $timestamps = false;
    protected $table = 'reservasi';

    protected $attributes = [
        'status_reservasi' => 'Reserved',
     ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pesanan extends Model
{
    protected $fillable = [
        'id_order', 'jumlah_order', 'status_order', 'id_reservasi', 
        'id_menu'
    ];

    protected $primaryKey = 'id_order';
    public $timestamps = false;
    protected $table = 'pesanan';

    protected $attributes = [
        'status_order' => 'ordered',
     ];
}

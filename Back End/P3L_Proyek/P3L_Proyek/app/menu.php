<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    protected $fillable = [
        'id_menu', 'tipe_jenis_menu', 'nama_menu', 'harga_menu', 
        'size_serving_menu', 'satuan', 'id_bahan', 'Deskripsi'
    ];

    protected $primaryKey = 'id_menu';
    public $timestamps = false;
    protected $table = 'menu';
}

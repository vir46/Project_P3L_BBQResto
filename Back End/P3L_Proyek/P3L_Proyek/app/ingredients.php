<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ingredients extends Model
{
    protected $fillable = [
        'id_bahan', 'nama_bahan', 'satuan', 'jumlah_bahan'
    ];

    protected $primaryKey = 'id_bahan';
    public $timestamps = false;
    protected $table = 'ingredients';

    protected $attributes = [
        'jumlah_bahan' => '0',
     ];
}

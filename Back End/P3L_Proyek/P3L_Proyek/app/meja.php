<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class meja extends Model
{
    protected $fillable = [
        'id_meja', 'status_meja', 'capacity',
    ];

    protected $primaryKey = 'id_meja';
    public $timestamps = false;
    protected $attributes = [
        'status_meja' => 'Available',
     ];
    protected $table = 'meja';
}

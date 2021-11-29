<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    protected $fillable = [
        'id_customer', 'nama_customer', 'email_customer', 
        'telepon_customer'
    ];

    protected $primaryKey = 'id_customer';
    public $timestamps = false;
    protected $table = 'customer';

}

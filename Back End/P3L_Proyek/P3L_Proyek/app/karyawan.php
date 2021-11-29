<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;

class karyawan extends model
{
    use HasApiTokens;

    protected $table = 'karyawan';
    protected $fillable = [
        'id_karyawan', 'nama_karyawan', 'role_karyawan', 
        'kelamin', 'telepon', 'email', 
        'status_karyawan', 'user_akun', 'password', 
        'tanggal_masuk'
    ];

    protected $primaryKey = 'id_karyawan';
    public $timestamps = false;
    protected $hidden = [
        'password'
    ];
    protected $attributes = [
        'status_karyawan' => 'Aktif',
     ];
}

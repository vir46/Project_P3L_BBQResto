<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'karyawan';
    protected $fillable = [
        'id_karyawan', 'nama_karyawan', 'role_karyawan', 
        'status_karyawan', 'user_akun', 'password', 
        'tanggal_masuk'
    ];

    protected $primaryKey = 'id_karyawan';
    public $timestamps = false;
    protected $hidden = [
        'password'
    ];
}

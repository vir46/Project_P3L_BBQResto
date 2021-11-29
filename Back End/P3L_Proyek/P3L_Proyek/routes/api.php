<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::post('registingredients','ingredientsController@register');
Route::get('ingredients','ingredientsController@available');
Route::get('ingredients/{id}','ingredientsController@cari');
Route::delete('ingredients/{id}','ingredientsController@hapus');
Route::put('ingredients/{id}','ingredientsController@update');
*/

// For Menu
    Route::post('registmenu','menuController@register');
    Route::get('menu','menuController@available');
    Route::get('menu/{id}','menuController@cari');
    Route::delete('menu/{id}','menuController@hapus');
    Route::put('menu/{id}','menuController@update');
    // For Bahan
    Route::post('registingredients','ingredientsController@register');
    Route::get('ingredients','ingredientsController@available');
    Route::get('ingredients/{id}','ingredientsController@cari');
    Route::delete('ingredients/{id}','ingredientsController@hapus');
    Route::put('ingredients/{id}','ingredientsController@update');
    Route::put('stockchange/{id}','ingredientsController@stockchange');
    // For Reservasi
    Route::post('addreservasi','reservasiController@register');
    Route::get('reservasi','reservasiController@available');
    Route::get('reservasi/{id}','reservasiController@cari');
    Route::delete('reservasi/{id}','reservasiController@hapus');
    Route::put('reservasi/{id}','reservasiController@update');
    Route::put('reservasistatus/{id}','reservasiController@updateStatus');
    // Transaksi Histori
    Route::post('newhistori','transaksi_historiController@register');
    Route::get('histori','transaksi_historiController@available');
    Route::get('reservasi/{id}','transaksi_historiController@cari');
    Route::delete('reservasi/{id}','transaksi_historiController@hapus');
    Route::put('reservasi/{id}','transaksi_historiController@update');
    // Pesanan
    Route::post('neworder','pesananController@register');
    Route::get('orderan','pesananController@available');
    Route::get('order/{id}','pesananController@cari');
    Route::delete('order/{id}','pesananController@hapus');
    Route::put('order/{id}','pesananController@update');
    Route::put('orderan/{id}','pesananController@updateStatus');

    Route::post('addpayment','pembayaranController@register'); //check
    Route::get('payment','pembayaranController@available'); //check
    Route::get('customer/{id}','customerController@cari'); //check
    Route::delete('customer/{id}','customerController@hapus'); //check
    Route::put('customer/{id}','customerController@update');  //check

    // Transaksi Pengadaan
    Route::post('newincome','transaksi_pengadaanController@register');
    Route::get('income','transaksi_pengadaanController@available');
    Route::get('incoming/{id}','transaksi_pengadaanController@cari');
    Route::delete('incoming/{id}','transaksi_pengadaanController@hapus');
    Route::put('incoming/{id}','transaksi_pengadaanController@update');

    Route::post('loginkaryawan','karyawanController@login');

Route::group(['middleware' => 'auth:api'], function(){
    // For Karyawan
    Route::post('registkaryawan','karyawanController@register');
    Route::get('karyawan','karyawanController@available');
    Route::get('karyawan/{id}','karyawanController@cari');
    Route::delete('karyawan/{id}','karyawanController@hapus');
    Route::put('karyawan/{id}','karyawanController@update');  
    // For Meja
    Route::post('tambahmeja','mejaController@register'); //check
    Route::get('meja','mejaController@available'); //check
    Route::get('meja/{id}','mejaController@cari'); //check
    Route::delete('meja/{id}','mejaController@hapus'); //check
    Route::put('meja/{id}','mejaController@update'); //check

    // For Customer
    Route::post('addcustomer','customerController@register'); //check
    Route::get('customer','customerController@available'); //check
    Route::get('customer/{id}','customerController@cari'); //check
    Route::delete('customer/{id}','customerController@hapus'); //check
    Route::put('customer/{id}','customerController@update');  //check

    // Payment

    
    
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

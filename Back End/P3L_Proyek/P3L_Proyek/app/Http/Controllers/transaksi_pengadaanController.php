<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\transaksi_pengadaan;
use Validator;

class transaksi_pengadaanController extends Controller
{
    public function register(Request $request)
    {
       $registrationData = $request->all() ;
       $validate = Validator::make($registrationData, [
            'tanggal_stock_in' => 'required',
            'total_harga_stock' => 'required',
            'jumlah_stock_transaksi' => 'required',
            'harga_satuan' => 'required',
            'id_bahan' => 'required',
            'id_karyawan' => 'required',
       ]);

       if($validate->fails()){
            return response(['message' => "Oops,, Something wrong here?"]);
       }

       $transaksi_pengadaan = Transaksi_pengadaan::create($registrationData);
       return response([
           'message' => 'Sukses!',
           'transaksi_pengadaan' => $transaksi_pengadaan,
       ],200);
    }

    public function available(Request $request)
    {
        $transaksi_pengadaan = Transaksi_pengadaan::all();

        if(count($transaksi_pengadaan) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksi_pengadaan
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function cari($id)
    {
        $transaksi_pengadaan = Transaksi_pengadaan::find($id);

        if(!is_null($transaksi_pengadaan)){
            return response([
                'message' => 'ambil data sukses',
                'data' => $transaksi_pengadaan
            ],200);
        }

        return response([
            'message' => 'Transaksi_pengadaan Not Found',
            'data'=> null
        ],404);
    }

    public function hapus($id)
    {
        $transaksi_pengadaan = Transaksi_pengadaan::find($id);

        if(is_null($transaksi_pengadaan)){
            return response([
                'message' => 'Transaksi_pengadaan Not Found',
                'data' => null
            ],404);
        }

        if($transaksi_pengadaan->delete()){
            return response([
                'message' => 'Data Transaksi_pengadaan Terhapus',
                'data' => $transaksi_pengadaan,
            ],200);
        }

        return response([
            'message' => 'Data Transaksi_pengadaan Gagal Dihapus',
            'data' => null,
        ]);
    }
    public function update(Request $request, $id)
    {
        $transaksi_pengadaan = Transaksi_pengadaan::find($id);
        if(is_null($transaksi_pengadaan)){
            return response([
                'message' => 'Transaksi_pengadaan Not Found',
                'data' => null
            ],404);
        }

        $ubahTransaksi_pengadaan = $request->all();
        $validate = Validator::make($ubahTransaksi_pengadaan, [
            'tanggal_stock_in' => 'required',
            'total_harga_stock' => 'required',
            'jumlah_stock_transaksi' => 'required',
            'harga_satuan' => 'required',
        ]);

        if($validate->fails()){
            // return respone(['message' => "Wrong Format"],404);
            return response([
                'value' => 0,
                'message' => 'Data Tidak Sesuai Rule',
            ]);
        }

        $transaksi_pengadaan->tanggal_stock_in = $ubahTransaksi_pengadaan['tanggal_stock_in'];
        $transaksi_pengadaan->total_harga_stock = $ubahTransaksi_pengadaan['total_harga_stock'];
        $transaksi_pengadaan->jumlah_stock_transaksi = $ubahTransaksi_pengadaan['jumlah_stock_transaksi'];
        $transaksi_pengadaan->harga_satuan = $ubahTransaksi_pengadaan['harga_satuan'];

        if($transaksi_pengadaan->save()){
            return response([
                'message' => 'Stock Success',
                'data' => $transaksi_pengadaan,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }
}

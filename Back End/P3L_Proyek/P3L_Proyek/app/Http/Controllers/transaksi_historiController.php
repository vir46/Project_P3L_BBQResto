<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\transaksi_histori;
use Validator;

class transaksi_historiController extends Controller
{
    public function register(Request $request)
    {
       $registrationData = $request->all() ;
       $validate = Validator::make($registrationData, [
            'jenis_perubahan_stock' => 'required',
            'tanggal_stock_berubah' => 'required',
            'jumlah_stock_perubahan' => 'required',
            'id_bahan' => 'required',
            'id_karyawan' => 'required',
       ]);

       if($validate->fails()){
            return response(['message' => "Oops,, Something wrong here?"]);
       }

       $transaksi_histori = Transaksi_histori::create($registrationData);
       return response([
           'message' => 'Sukses!',
           'transaksi_histori' => $transaksi_histori,
       ],200);
    }

    public function available(Request $request)
    {
        $transaksi_histori = Transaksi_histori::all();

        if(count($transaksi_histori) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksi_histori
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function cari($id)
    {
        $transaksi_histori = Transaksi_histori::find($id);

        if(!is_null($transaksi_histori)){
            return response([
                'message' => 'ambil data sukses',
                'data' => $transaksi_histori
            ],200);
        }

        return response([
            'message' => 'Transaksi_histori Not Found',
            'data'=> null
        ],404);
    }

    public function hapus($id)
    {
        $transaksi_histori = Transaksi_histori::find($id);

        if(is_null($transaksi_histori)){
            return response([
                'message' => 'Transaksi_histori Not Found',
                'data' => null
            ],404);
        }

        if($transaksi_histori->delete()){
            return response([
                'message' => 'Data Transaksi_histori Terhapus',
                'data' => $transaksi_histori,
            ],200);
        }

        return response([
            'message' => 'Data Transaksi_histori Gagal Dihapus',
            'data' => null,
        ]);
    }
    public function stockchange(Request $request, $id)
    {
        $transaksi_histori = Transaksi_histori::find($id);
        if(is_null($transaksi_histori)){
            return response([
                'message' => 'Transaksi_histori Not Found',
                'data' => null
            ],404);
        }

        $ubahTransaksi_histori = $request->all();
        $validate = Validator::make($ubahTransaksi_histori, [
            'jumlah_bahan' => 'required',
        ]);

        if($validate->fails()){
            // return respone(['message' => "Wrong Format"],404);
            return response([
                'value' => 0,
                'message' => 'Data Tidak Sesuai Rule',
            ]);
        }

        $transaksi_histori->jumlah_bahan = $ubahTransaksi_histori['jumlah_bahan'];

        if($transaksi_histori->save()){
            return response([
                'message' => 'Stock Success',
                'data' => $transaksi_histori,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $transaksi_histori = Transaksi_histori::find($id);
        if(is_null($transaksi_histori)){
            return response([
                'message' => 'Transaksi_histori Not Found',
                'data' => null
            ],404);
        }

        $ubahTransaksi_histori = $request->all();
        $validate = Validator::make($ubahTransaksi_histori, [
            'nama_bahan' => 'required',
            'satuan' => 'required'
        ]);

        if($validate->fails()){
            // return respone(['message' => "Wrong Format"],404);
            return response([
                'value' => 0,
                'message' => 'Data Tidak Sesuai Rule',
            ]);
        }

        $transaksi_histori->nama_bahan = $ubahTransaksi_histori['nama_bahan'];
        $transaksi_histori->satuan = $ubahTransaksi_histori['satuan'];

        if($transaksi_histori->save()){
            return response([
                'message' => 'Update Transaksi_histori Success',
                'data' => $transaksi_histori,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\pesanan;
use Validator;

class pesananController extends Controller
{
    public function register(Request $request)
    {
       $registrationData = $request->all() ;
       $validate = Validator::make($registrationData, [
            'jumlah_order' => 'required',
            'id_reservasi' => 'required',
            'jumlah_order' => 'required',
            'id_menu' => 'required',
       ]);

       if($validate->fails()){
            return response(['message' => "Oops,, Something wrong here?"]);
       }

       $pesanan = Pesanan::create($registrationData);
       return response([
           'message' => 'Pesanan ditambahkan',
           'pesanan' => $pesanan,
       ],200);
    }

    public function available(Request $request)
    {
        $pesanan = Pesanan::all();

        if(count($pesanan) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pesanan
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function cari($id)
    {
        $pesanan = Pesanan::find($id);

        if(!is_null($pesanan)){
            return response([
                'message' => 'ambil data sukses',
                'data' => $pesanan
            ],200);
        }

        return response([
            'message' => 'Wrong Number of Pesanan',
            'data'=> null
        ],404);
    }



    public function hapus($id)
    {
        $pesanan = Pesanan::find($id);

        if(is_null($pesanan)){
            return response([
                'message' => 'Pesanan Not Found',
                'data' => null
            ],404);
        }

        if($pesanan->delete()){
            return response([
                'message' => 'Pesanan Terhapus',
                'data' => $pesanan,
            ],200);
        }

        return response([
            'message' => 'Data Pesanan Gagal Dihapus',
            'data' => null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::find($id);
        if(is_null($pesanan)){
            return response([
                'message' => 'Pesanan Not Found',
                'data' => null
            ],404);
        }

        $ubahPesanan = $request->all();
        $validate = Validator::make($ubahPesanan, [
            'status_order' => 'required',
        ]);

        if($validate->fails()){
            // return respone(['message' => "Wrong Format"],404);
            return response([
                'value' => 0,
                'message' => 'Data Tidak Sesuai Rule',
            ]);
        }

        $pesanan->status_order = $ubahPesanan['status_order'];

        if($pesanan->save()){
            return response([
                'message' => 'Update Pesanan Success',
                'data' => $pesanan,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::find($id);
        if(is_null($pesanan)){
            return response([
                'message' => 'Pesanan Not Found',
                'data' => null
            ],404);
        }

        $ubahPesanan = $request->all();
        $validate = Validator::make($ubahPesanan, [
            'status_order' => 'required',
        ]);

        if($validate->fails()){
            // return respone(['message' => "Wrong Format"],404);
            return response([
                'value' => 0,
                'message' => 'Data Tidak Sesuai Rule',
            ]);
        }

        $pesanan->status_order = $ubahPesanan['status_order'];

        if($pesanan->save()){
            return response([
                'message' => 'Update Pesanan Success',
                'data' => $pesanan,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\pembayaran;
use Validator;

class pembayaranController extends Controller
{
    public function register(Request $request)
    {
       $registrationData = $request->all() ;
       $validate = Validator::make($registrationData, [
            'total_pembayaran' => 'required',
            'total_kembalian' => 'required',
            'jenis_pembayaran' => 'required',
            'tanggal_bayar' => 'required',
            'id_order' => 'required',
            'id_info' => 'required',    
            'Verif_Code' => 'required', 
       ]);

       if($validate->fails()){
            return response(['message' => "Oops,, Something wrong here?"]);
       }

       $pembayaran = Pembayaran::create($registrationData);
       return response([
           'message' => 'Pembayaran ditambahkan',
           'pembayaran' => $pembayaran,
       ],200);
    }

    public function available(Request $request)
    {
        $pembayaran = Pembayaran::all();

        if(count($pembayaran) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pembayaran
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function cari($id)
    {
        $pembayaran = Pembayaran::find($id);

        if(!is_null($pembayaran)){
            return response([
                'message' => 'ambil data sukses',
                'data' => $pembayaran
            ],200);
        }

        return response([
            'message' => 'Wrong Number of Pembayaran',
            'data'=> null
        ],404);
    }

    public function hapus($id)
    {
        $pembayaran = Pembayaran::find($id);

        if(is_null($pembayaran)){
            return response([
                'message' => 'Pembayaran Not Found',
                'data' => null
            ],404);
        }

        if($pembayaran->delete()){
            return response([
                'message' => 'Pembayaran Terhapus',
                'data' => $pembayaran,
            ],200);
        }

        return response([
            'message' => 'Data Pembayaran Gagal Dihapus',
            'data' => null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::find($id);
        if(is_null($pembayaran)){
            return response([
                'message' => 'Pembayaran Not Found',
                'data' => null
            ],404);
        }

        $ubahPembayaran = $request->all();
        $validate = Validator::make($ubahPembayaran, [
            'jumlah_order' => 'required',
            'id_menu' => 'required',
        ]);

        if($validate->fails()){
            // return respone(['message' => "Wrong Format"],404);
            return response([
                'value' => 0,
                'message' => 'Data Tidak Sesuai Rule',
            ]);
        }

        $pembayaran->capacity = $ubahPembayaran['capacity'];

        if($pembayaran->save()){
            return response([
                'message' => 'Update Pembayaran Success',
                'data' => $pembayaran,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }
}

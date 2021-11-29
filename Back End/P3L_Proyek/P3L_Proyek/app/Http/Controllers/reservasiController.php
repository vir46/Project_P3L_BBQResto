<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\reservasi;
use Validator;

class reservasiController extends Controller
{
    public function register(Request $request)
    {
       $registrationData = $request->all() ;
       $validate = Validator::make($registrationData, [
        'tanggal_pencatatan_reservasi' => 'required',
        'jam_pencatatan_reservasi' => 'required',
        'tanggal_reservasi' => 'required',
        'sesi_reservasi' => 'required',
        'id_customer' => 'required',
        'id_meja' => 'required',
        'id_karyawan' => 'required',
       ]);

       if($validate->fails()){
            return response(['message' => "Oops,, Something wrong here?"]);
       }

       $reservasi = Reservasi::create($registrationData);
       return response([
           'message' => 'Terdaftar!',
           'reservasi' => $reservasi,
       ],200);
    }

    public function available(Request $request)
    {
        $reservasi = Reservasi::all();

        if(count($reservasi) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $reservasi
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function cari($id)
    {
        $reservasi = Reservasi::find($id);

        if(!is_null($reservasi)){
            return response([
                'message' => 'ambil data sukses',
                'data' => $reservasi
            ],200);
        }

        return response([
            'message' => 'Reservasi Not Found',
            'data'=> null
        ],404);
    }

    public function hapus($id)
    {
        $reservasi = Reservasi::find($id);

        if(is_null($reservasi)){
            return response([
                'message' => 'Reservasi Not Found',
                'data' => null
            ],404);
        }

        if($reservasi->delete()){
            return response([
                'message' => 'Data Reservasi Terhapus',
                'data' => $reservasi,
            ],200);
        }

        return response([
            'message' => 'Data Reservasi Gagal Dihapus',
            'data' => null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $reservasi = Reservasi::find($id);
        if(is_null($reservasi)){
            return response([
                'message' => 'Reservasi Not Found',
                'data' => null
            ],404);
        }

        $ubahReservasi = $request->all();
        $validate = Validator::make($ubahReservasi, [
            'tanggal_reservasi' => 'required',
            'sesi_reservasi' => 'required',
            'id_customer' => 'required',
            'id_meja' => 'required',
        ]);

        if($validate->fails()){
            // return respone(['message' => "Wrong Format"],404);
            return response([
                'value' => 0,
                'message' => 'Data Tidak Sesuai Rule',
            ]);
        }

        $reservasi->tanggal_reservasi = $ubahReservasi['tanggal_reservasi'];
        $reservasi->sesi_reservasi = $ubahReservasi['sesi_reservasi'];
        $reservasi->id_customer = $ubahReservasi['id_customer'];
        $reservasi->id_meja = $ubahReservasi['id_meja'];

        if($reservasi->save()){
            return response([
                'message' => 'Update Reservasi Success',
                'data' => $reservasi,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $reservasi = Reservasi::find($id);
        if(is_null($reservasi)){
            return response([
                'message' => 'Reservasi Not Found',
                'data' => null
            ],404);
        }

        $ubahReservasi = $request->all();
        $validate = Validator::make($ubahReservasi, [
            'status_reservasi' => 'required',
        ]);

        if($validate->fails()){
            // return respone(['message' => "Wrong Format"],404);
            return response([
                'value' => 0,
                'message' => 'Data Tidak Sesuai Rule',
            ]);
        }

        $reservasi->status_reservasi = $ubahReservasi['status_reservasi'];

        if($reservasi->save()){
            return response([
                'message' => 'Update Status Reservasi Success',
                'data' => $reservasi,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }
}

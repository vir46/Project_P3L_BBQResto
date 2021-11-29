<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\meja;
use Validator;

class mejaController extends Controller
{
    public function register(Request $request)
    {
       $registrationData = $request->all() ;
       $validate = Validator::make($registrationData, [
        'capacity' => 'max:9|integer',
       ]);

       if($validate->fails()){
            return response(['message' => "Oops,, Something wrong here?"]);
       }

       $meja = Meja::create($registrationData);
       return response([
           'message' => 'Meja ditambahkan',
           'meja' => $meja,
       ],200);
    }

    public function available(Request $request)
    {
        $meja = Meja::all();

        if(count($meja) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $meja
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function cari($id)
    {
        $meja = Meja::find($id);

        if(!is_null($meja)){
            return response([
                'message' => 'ambil data sukses',
                'data' => $meja
            ],200);
        }

        return response([
            'message' => 'Wrong Number of Table',
            'data'=> null
        ],404);
    }

    public function hapus($id)
    {
        $meja = Meja::find($id);

        if(is_null($meja)){
            return response([
                'message' => 'Table Not Found',
                'data' => null
            ],404);
        }

        if($meja->delete()){
            return response([
                'message' => 'Meja Terhapus',
                'data' => $meja,
            ],200);
        }

        return response([
            'message' => 'Data Meja Gagal Dihapus',
            'data' => null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $meja = Meja::find($id);
        if(is_null($meja)){
            return response([
                'message' => 'Meja Not Found',
                'data' => null
            ],404);
        }

        $ubahMeja = $request->all();
        $validate = Validator::make($ubahMeja, [
            'capacity' => 'max:10|integer'
        ]);

        if($validate->fails()){
            // return respone(['message' => "Wrong Format"],404);
            return response([
                'value' => 0,
                'message' => 'Data Tidak Sesuai Rule',
            ]);
        }

        $meja->capacity = $ubahMeja['capacity'];

        if($meja->save()){
            return response([
                'message' => 'Update Meja Success',
                'data' => $meja,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }
}

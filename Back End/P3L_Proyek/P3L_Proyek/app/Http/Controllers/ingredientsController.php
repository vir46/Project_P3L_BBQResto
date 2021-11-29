<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\ingredients;
use App\menu;
use Validator;

class ingredientsController extends Controller
{
    public function register(Request $request)
    {
       $registrationData = $request->all() ;
       $validate = Validator::make($registrationData, [
        'nama_bahan' => 'required',
        'satuan' => 'required',
       ]);

       if($validate->fails()){
            return response(['message' => "Oops,, Something wrong here?"]);
       }

       $ingredients = Ingredients::create($registrationData);
       return response([
           'message' => 'Bahan Terdaftar!',
           'ingredients' => $ingredients,
       ],200);
    }

    public function available(Request $request)
    {
        $ingredients = Ingredients::all();

        if(count($ingredients) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $ingredients
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function cari($id)
    {
        $ingredients = Ingredients::find($id);

        if(!is_null($ingredients)){
            return response([
                'message' => 'ambil data sukses',
                'data' => $ingredients
            ],200);
        }

        return response([
            'message' => 'Ingredients Not Found',
            'data'=> null
        ],404);
    }

    public function hapus($id)
    {
        $ingredients = Ingredients::find($id);

        if(is_null($ingredients)){
            return response([
                'message' => 'Ingredients Not Found',
                'data' => null
            ],404);
        }

        if($ingredients->delete()){
            return response([
                'message' => 'Data Ingredients Terhapus',
                'data' => $ingredients,
            ],200);
        }

        return response([
            'message' => 'Data Ingredients Gagal Dihapus',
            'data' => null,
        ]);
    }
    public function stockchange(Request $request, $id)
    {
        $ingredients = Ingredients::find($id);
        if(is_null($ingredients)){
            return response([
                'message' => 'Ingredients Not Found',
                'data' => null
            ],404);
        }

        $ubahIngredients = $request->all();
        $validate = Validator::make($ubahIngredients, [
            'jumlah_bahan' => 'required',
        ]);

        if($validate->fails()){
            // return respone(['message' => "Wrong Format"],404);
            return response([
                'value' => 0,
                'message' => 'Data Tidak Sesuai Rule',
            ]);
        }

        $ingredients->jumlah_bahan = $ubahIngredients['jumlah_bahan'];

        if($ingredients->save()){
            return response([
                'message' => 'Stock Success',
                'data' => $ingredients,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $ingredients = Ingredients::find($id);
        if(is_null($ingredients)){
            return response([
                'message' => 'Ingredients Not Found',
                'data' => null
            ],404);
        }

        $ubahIngredients = $request->all();
        $validate = Validator::make($ubahIngredients, [
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

        $ingredients->nama_bahan = $ubahIngredients['nama_bahan'];
        $ingredients->satuan = $ubahIngredients['satuan'];

        if($ingredients->save()){
            return response([
                'message' => 'Update Ingredients Success',
                'data' => $ingredients,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }
}

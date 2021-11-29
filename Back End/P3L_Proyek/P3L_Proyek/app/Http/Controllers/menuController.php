<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\menu;
use Validator;

class menuController extends Controller
{
    public function register(Request $request)
    {
       $registrationData = $request->all() ;
       $validate = Validator::make($registrationData, [
        'tipe_jenis_menu' => 'required',
        'nama_menu' => 'required', 
        'harga_menu' => 'required', 
        'size_serving_menu' => 'required',
        'satuan' => 'required',
        'id_bahan' => 'required',
        'Deskripsi' => 'required',
       ]);

       if($validate->fails()){
            return response(['message' => "Oops,, Something wrong here?"]);
       }

       $menu = Menu::create($registrationData);
       return response([
           'message' => 'Menu Terdaftar!',
           'menu' => $menu,
       ],200);
    }

    public function available(Request $request)
    {
        $menu = Menu::all();

        if(count($menu) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $menu
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function cari($id)
    {
        $menu = Menu::find($id);

        if(!is_null($menu)){
            return response([
                'message' => 'ambil data sukses',
                'data' => $menu
            ],200);
        }

        return response([
            'message' => 'Menu Not Found',
            'data'=> null
        ],404);
    }

    public function hapus($id)
    {
        $menu = Menu::find($id);

        if(is_null($menu)){
            return response([
                'message' => 'Menu Not Found',
                'data' => null
            ],404);
        }

        if($menu->delete()){
            return response([
                'message' => 'Data Menu Terhapus',
                'data' => $menu,
            ],200);
        }

        return response([
            'message' => 'Data Menu Gagal Dihapus',
            'data' => null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::find($id);
        if(is_null($menu)){
            return response([
                'message' => 'Menu Not Found',
                'data' => null
            ],404);
        }

        $ubahMenu = $request->all();
        $validate = Validator::make($ubahMenu, [
            'harga_menu' => 'required',
            'tipe_jenis_menu' => 'required',
            'nama_menu' => 'required',
            'size_serving_menu' => 'required',
            'satuan' => 'required',
            'id_bahan' => 'required',
            'Deskripsi' => 'required',    
        ]);

        if($validate->fails()){
            // return respone(['message' => "Wrong Format"],404);
            return response([
                'value' => 0,
                'message' => 'Data Tidak Sesuai Rule',
            ]);
        }

        $menu->harga_menu = $ubahMenu['harga_menu'];
        $menu->tipe_jenis_menu = $ubahMenu['tipe_jenis_menu'];
        $menu->nama_menu = $ubahMenu['nama_menu'];
        $menu->size_serving_menu = $ubahMenu['size_serving_menu'];
        $menu->satuan = $ubahMenu['satuan'];
        $menu->id_bahan = $ubahMenu['id_bahan'];
        $menu->Deskripsi = $ubahMenu['Deskripsi'];

        if($menu->save()){
            return response([
                'message' => 'Update Menu Success',
                'data' => $menu,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }
}

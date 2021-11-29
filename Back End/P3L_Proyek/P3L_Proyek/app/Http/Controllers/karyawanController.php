<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\karyawan;
use App\User;
use Validator;

class karyawanController extends Controller
{
    public function register(Request $request)
    {
       $registrationData = $request->all() ;
       $validate = Validator::make($registrationData, [
        'nama_karyawan' => 'required|max:60',
        'kelamin' => 'required',
        'telepon' => 'required',
        'email' => 'required',
        'role_karyawan' => 'required',
        'user_akun' => 'required',
        'password' => 'required|max:16',
        'tanggal_masuk' => 'required'
       ]);

       if($validate->fails()){
            return response(['message' => "Oops,, Something wrong here?"]);
       }

       $registrationData['password'] = bcrypt($request->password);
       $karyawan = Karyawan::create($registrationData);
       return response([
           'message' => 'Karyawan Baru Berhasil Terdaftar!',
           'karyawan' => $karyawan,
       ],200);
    }

    public function login(Request $request){
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'user_akun' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails()){
            return response(['message' => "Something Wrong, I can feel it"]);
        }

        if(!Auth::attempt($loginData)){
            return response(['message' => "Invalid Credentials"],401);
        }
        
        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'karyawan' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function available(Request $request)
    {
        $karyawan = Karyawan::all();

        if(count($karyawan) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $karyawan
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cari($id)
    {
        $karyawan = Karyawan::find($id);

        if(!is_null($karyawan)){
            return response([
                'message' => 'ambil data sukses',
                'data' => $karyawan
            ],200);
        }

        return response([
            'message' => 'Karyawan Not Found',
            'data'=> null
        ],404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function hapus($id)
    {
        $karyawan = Karyawan::find($id);

        if(is_null($karyawan)){
            return response([
                'message' => 'Karyawan Not Found',
                'data' => null
            ],404);
        }

        if($karyawan->delete()){
            return response([
                'message' => 'Data Karyawan Terhapus',
                'data' => $karyawan,
            ],200);
        }

        return response([
            'message' => 'Data Karyawan Gagal Dihapus',
            'data' => null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::find($id);
        if(is_null($karyawan)){
            return response([
                'message' => 'Karyawan Not Found',
                'data' => null
            ],404);
        }

        $ubahKaryawan = $request->all();
        $validate = Validator::make($ubahKaryawan, [
            'user_akun' => ['max:60', Rule::unique('karyawan')->ignore($karyawan)],
            'kelamin' => 'required',
            'telepon' => 'required',
            'email' => 'required',
            'status_karyawan' => 'max:10'
        ]);

        if($validate->fails()){
            return respone(['message' => $validate->errors()],404);
        }

        $karyawan->user_akun = $ubahKaryawan['user_akun'];
        $karyawan->kelamin = $ubahKaryawan['kelamin'];
        $karyawan->telepon = $ubahKaryawan['telepon'];
        $karyawan->email = $ubahKaryawan['email'];
        $karyawan->status_karyawan = $ubahKaryawan['status_karyawan'];

        if($karyawan->save()){
            return response([
                'message' => 'Update Karyawan Success',
                'data' => $karyawan,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }

}

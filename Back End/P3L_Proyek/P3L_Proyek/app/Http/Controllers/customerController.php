<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\customer;
use Validator;

class customerController extends Controller
{
    public function register(Request $request)
    {
       $registrationData = $request->all() ;
       $validate = Validator::make($registrationData, [
        'nama_customer' => 'required|max:60',
        'email_customer' => 'required|email:rfc,dns|unique:customer',
        'telepon_customer' => 'required|min:9|max:12',
       ]);

       if($validate->fails()){
            return response(['message' => "Oops,, Something wrong here?"]);
       }

       $customer = Customer::create($registrationData);
       return response([
           'message' => 'Customer Terdaftar!',
           'customer' => $customer,
       ],200);
    }

    public function available(Request $request)
    {
        $customer = Customer::all();

        if(count($customer) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $customer
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function cari($id)
    {
        $customer = Customer::find($id);

        if(!is_null($customer)){
            return response([
                'message' => 'ambil data sukses',
                'data' => $customer
            ],200);
        }

        return response([
            'message' => 'Customer Not Found',
            'data'=> null
        ],404);
    }

    public function hapus($id)
    {
        $customer = Customer::find($id);

        if(is_null($customer)){
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ],404);
        }

        if($customer->delete()){
            return response([
                'message' => 'Data Customer Terhapus',
                'data' => $customer,
            ],200);
        }

        return response([
            'message' => 'Data Customer Gagal Dihapus',
            'data' => null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        if(is_null($customer)){
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ],404);
        }

        $ubahCustomer = $request->all();
        $validate = Validator::make($ubahCustomer, [
            'nama_customer' => ['max:60', 'min:4', Rule::unique('customer')->ignore($customer)],
            'telepone_customer' => 'max:9'
        ]);

        if($validate->fails()){
            // return respone(['message' => "Wrong Format"],404);
            return response([
                'value' => 0,
                'message' => 'Data Tidak Sesuai Rule',
            ]);
        }

        $customer->nama_customer = $ubahCustomer['nama_customer'];
        $customer->telepon_customer = $ubahCustomer['telepon_customer'];

        if($customer->save()){
            return response([
                'message' => 'Update Customer Success',
                'data' => $customer,
            ],200);
        }
        return response([
            'message' => 'Changing Data Failed',
            'data' => null,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ormawa;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //fungsi mengembalikan view admin
    public function dataHima(){
        $ormawas = Ormawa::all();
        return view('dataadmin', ['ormawas' => $ormawas]);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'id_ormawa'     => 'required',
            'nama_ormawa'   => 'required',
            'nama_singkatan'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $post = Post::create([
            'id_ormawa'     => $request->id_ormawa, 
            'nama_ormawa'   => $request->nama_ormawa,
            'nama_singkatan'   => $request->nama_singkatan,
        ]);

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
            'data'    => $post  
        ]);
    }

}

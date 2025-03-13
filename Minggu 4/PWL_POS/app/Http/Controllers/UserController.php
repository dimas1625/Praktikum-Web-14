<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $user = UserModel::all();
        return view('user', ['data' => $user]);
    }

    // Tambahkan method tambah untuk menampilkan form tambah user
    public function tambah(){
        return view('user_tambah');
    }
    // Method untuk menyimpan data user dari form tambah
    public function tambah_simpan(Request $request) //menerima form melalui inputan
    {
        UserModel::create([ //membuat data baru
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make('$request->password'),
            'level_id' => $request->level_id
        ]);
        return redirect('/user');   //mengirimkan tampilan kepada view 'user.blade.php'
    }
    public function ubah($id)   //mengambil data yang sesuai dengan ID yang dipilih
    {
        $user = UserModel::find($id);   //mencari ID yang dipilih
        return view('user_ubah', ['data' => $user]);    //mengirimkan tampilan ke 'user.blade.php'
    }
    public function ubah_simpan($id, Request $request){

        $user = UserModel :: find($id);

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make('$request->password');
        $user->level_id = $request->level_id;

        $user->save();

        return redirect('/user');
    }
}
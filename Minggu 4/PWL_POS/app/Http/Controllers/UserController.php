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
}
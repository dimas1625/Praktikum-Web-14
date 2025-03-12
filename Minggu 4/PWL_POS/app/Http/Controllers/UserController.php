<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){

        $manager = UserModel::firstOrNew(
            [
            'username' => 'manager33',
            'nama'     => 'Manager Tiga Tiga',
            'password' => Hash::make('12345'),
            'level_id' => 2
            ]
        );

        // Kirim data ke view
        return view('user', ['data' => $manager]);
    }   
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
{
    // validasi input
    $validator = Validator::make($request->all(), [
        'username' => 'required',
        'password' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $credentials = $request->only('username', 'password');

    // coba generate token
    if (! $token = auth()->guard('api')->attempt($credentials)) {
        return response()->json([
            'success' => false,
            'message' => 'Username atau Password Anda salah'
        ], 401);
    }

    // *** PENTING: ini di luar blok if gagal ***
    return response()->json([
        'success' => true,
        'user'    => auth()->guard('api')->user(),
        'token'   => $token
    ], 200);
}
}
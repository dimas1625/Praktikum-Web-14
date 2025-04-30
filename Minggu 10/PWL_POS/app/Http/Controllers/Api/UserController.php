<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return UserModel::all();
    }

    public function store(Request $request)
    {
        // Anda bisa men-hash password di sini atau di Model via mutator
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $user = UserModel::create($data);
        return response()->json($user, 201);
    }

    public function show(UserModel $user)
    {
        return $user;
    }

    public function update(Request $request, UserModel $user)
    {
        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $user->update($data);
        return response()->json($user);
    }

    public function destroy(UserModel $user)
    {
        $user->delete();
        return response()->json(['success'=>true,'message'=>'User terhapus']);
    }
}
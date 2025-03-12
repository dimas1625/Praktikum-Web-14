<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile($id, $name)
    {
        // Kirim data parameter ke view user.blade.php
        return view('user', compact('id', 'name'));
    }
}

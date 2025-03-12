<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() {
        return "Selamat Datang";
    }

    public function about() {
        return "Nama : Muhammad Dimas Ajie Nugroho NIM : 2341720033";
    }

    public function articles($id) {
        return "Halaman Artikel dengan Id $id";
    }
}

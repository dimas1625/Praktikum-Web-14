<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriModel;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index() 
    { 
        return KategoriModel::all(); 
    }
    public function store(Request $r) {
        $kat = KategoriModel::create($r->all());
        return response()->json($kat, 201);
    }
    
    public function show(KategoriModel $kategori) { 
        return $kategori; 
    }

    public function update(Request $r, KategoriModel $kategori) {
        $kategori->update($r->all());
        return response()->json($kategori);
    }

    public function destroy(KategoriModel $kategori) {
        $kategori->delete();
        return response()->json(['success'=>true,'message'=>'Kategori terhapus']);
    }
}

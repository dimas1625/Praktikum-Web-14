<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangModel;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index() 
    { 
        return BarangModel::all(); 
    }
    public function store(Request $r) {
        $brg = BarangModel::create($r->all());
        return response()->json($brg, 201);
    }

    public function show(BarangModel $barang) { 
        return $barang; 
    }

    public function update(Request $r, BarangModel $barang) {
        $barang->update($r->all());
        return response()->json($barang);
    }
    
    public function destroy(BarangModel $barang) {
        $barang->delete();
        return response()->json(['success'=>true,'message'=>'Barang terhapus']);
    }
}

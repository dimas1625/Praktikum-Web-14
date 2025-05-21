<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\BarangModel;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index() 
    { 
        return BarangModel::all(); 
    }
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'kategori_id'  => ['required', 'integer'],
            'barang_kode'  => ['required', 'string', 'min:3', 'max:10', 'unique:m_barang,barang_kode'],
            'barang_nama'  => ['required', 'string', 'min:3', 'max:100'],
            'harga_beli'   => ['required', 'numeric', 'min:0'],
            'harga_jual'   => ['required', 'numeric', 'min:0'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');

        $barang = BarangModel::create([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'barang_stok' => 0,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'image' => $image->hashName(),
        ]);
        return response()->json($request, 201);
    }

    public function show(BarangModel $barang) { 
        return $barang; 
    }

    public function update(Request $request, BarangModel $barang) {
        $barang->update($request->all());
        return response()->json($barang);
    }
    
    public function destroy(BarangModel $barang) {
        $barang->delete();
        return response()->json(['success'=>true,'message'=>'Barang terhapus']);
    }
}

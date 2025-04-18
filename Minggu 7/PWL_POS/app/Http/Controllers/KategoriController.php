<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    public function index()
     {
         $breadcrumb = (object) [
             'title' => 'Daftar Kategori',
             'list' => ['Home', 'Kategori']
         ];
 
         $page = (object) [
             'title' => 'Daftar Kategori yang terdaftar di sistem'
         ];
 
         $activeMenu = 'kategori';
 
         return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
     }
 
     public function list(Request $request)
     {
         $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');
 
         if ($request->kategori_nama) {
             $kategori->where('kategori_nama', 'like', '%' . $request->kategori_nama . '%');
         }
 
         return DataTables::of($kategori)
             ->addIndexColumn()
             ->addColumn('aksi', function ($kategori) {
                  // $btn = '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                 // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '">'
                 //     . csrf_field() . method_field('DELETE') .
                 //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                 $btn = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                     '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                     '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                 $btn .= '<bsutton onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                     '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }
 
     public function create()
     {
         $breadcrumb = (object) [
             'title' => 'Tambah Kategori',
             'list' => ['Home', 'Kategori', 'Tambah']
         ];
 
         $page = (object) [
             'title' => 'Tambah Kategori Baru'
         ];
 
         $activeMenu = 'kategori';
 
         return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
     }
 
     public function store(Request $request)
     {
         $request->validate([
             'kategori_kode' => 'required|unique:m_kategori',
             'kategori_nama' => 'required'
         ]);
 
         KategoriModel::create($request->all());
 
         return redirect('/kategori')->with('status', 'Data kategori berhasil ditambahkan!');
     }
 
     public function edit($id)
     {
         $breadcrumb = (object) [
             'title' => 'Edit Kategori',
             'list' => ['Home', 'Kategori', 'Edit']
         ];
 
         $page = (object) [
             'title' => 'Edit Kategori'
         ];
 
         $activeMenu = 'kategori';
 
         $kategori = KategoriModel::find($id);
 
         return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
     }
 
     public function update(Request $request, $id)
     {
         $request->validate([
             'kategori_kode' => 'required|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
             'kategori_nama' => 'required'
         ]);
 
         KategoriModel::find($id)->update($request->all());
 
         return redirect('/kategori')->with('status', 'Data kategori berhasil diubah!');
     }
 
     public function destroy($id)
     {
         $check = KategoriModel::find($id);
         if (!$check) {
             return redirect('/kategori')->with('error', 'Data user tidak ditemukan');
         }
 
         try {
             KategoriModel::destroy($id);
 
             return redirect('/kategori')->with('success', 'Data user berhasil dihapus');
         } catch (\Exception $e) {
             return redirect('/kategori')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
         }
    }
    public function create_ajax()
     {
         return view('kategori.create_ajax',);
     }
 
     public function store_ajax(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode',
                 'kategori_nama' => 'required|string|min:3',
 
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => "Validasi gagal",
                     'msgField' => $validator->errors()
                 ]);
             }
 
             KategoriModel::create($request->all());
 
             return response()->json([
                 'status' => true,
                 'message' => 'Data kategori berhasil disimpan'
             ]);
         }
         redirect('/kategori');
     }
 
     public function edit_ajax(string $id)
     {
         $kategori = KategoriModel::find($id);
         return view('kategori.edit_ajax', [
             'kategori' => $kategori,
         ]);
     }
 
     public function update_ajax(Request $request, $id)
     {
         // cek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'kategori_kode' => 'required|string|min:3',
                 'kategori_nama' => 'required|string|min:3',
             ];
             // use Illuminate\Support\Facades\Validator;
             $validator = Validator::make($request->all(), $rules);
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false, // respon json, true: berhasil, false: gagal
                     'message' => 'Validasi gagal.',
                     'msgField' => $validator->errors() // menunjukkan field mana yang error
                 ]);
             }
             $check = KategoriModel::find($id);
             if ($check) {
                 $check->update($request->all());
                 return response()->json([
                     'status' => true,
                     'message' => 'Data berhasil diupdate'
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data tidak ditemukan'
                 ]);
             }
         }
 
         return response()->json([
             'status' => false,
             'message' => 'Request Bukan Ajax'
         ]);
 
         redirect('/');
     }
 
     public function confirm_ajax(string $id)
     {
         $kategori = KategoriModel::find($id);
 
         return view('kategori.confirm_ajax', [
             'kategori' => $kategori
         ]);
     }
 
     public function delete_ajax(Request $request, $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $kategori = KategoriModel::find($id);
 
             if ($kategori) {
                 $kategori->delete();
                 return response()->json([
                     'status' => true,
                     'message' => 'Data berhasil dihapus'
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data tidak ditemukan'
                 ]);
             }
         }
         redirect('/');
     }
}    
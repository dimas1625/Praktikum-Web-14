<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    public static function index()
    {
        
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar Level yang terdaftar di sistem'
        ];

        $activeMenu = 'level';
        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
     }
 
     public function list(Request $request)
     {
         $level = LevelModel::select('level_id', 'level_kode', 'level_nama');
 
         if ($request->level_nama) {
             $level->where('level_nama', 'like', '%' . $request->level_nama . '%');
         }
 
         return DataTables::of($level)
             ->addIndexColumn()
             ->addColumn('aksi', function ($level) {
                 // $btn = '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                 // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level/' . $level->level_id) . '">'
                 //     . csrf_field() . method_field('DELETE') .
                 //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                 $btn = '<button onclick="modalAction(\'' . url('/level/' . $level->level_id .
                     '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id .
                     '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id .
                     '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }
 
     public function create()
     {
         $breadcrumb = (object) [
             'title' => 'Tambah Level',
             'list' => ['Home', 'Level', 'Tambah']
         ];
 
         $page = (object) [
             'title' => 'Tambah Level Baru'
         ];
 
         $activeMenu = 'level';
 
         return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
     }
 
     public function store(Request $request)
     {
         $request->validate([
             'level_kode' => 'required|unique:m_level',
             'level_nama' => 'required'
         ]);
 
         LevelModel::create($request->all());
 
         return redirect('/level')->with('status', 'Data level berhasil ditambahkan!');
     }
 
     public function edit($id)
     {
         $breadcrumb = (object) [
             'title' => 'Edit Level',
             'list' => ['Home', 'Level', 'Edit']
         ];
 
         $page = (object) [
             'title' => 'Edit Level'
         ];
 
         $activeMenu = 'level';
 
         $level = LevelModel::find($id);
 
         return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'level' => $level]);
     }
 
     public function update(Request $request, $id)
     {
         $request->validate([
             'level_kode' => 'required|unique:m_level,level_kode,' . $id . ',level_id',
             'level_nama' => 'required'
         ]);
 
         LevelModel::find($id)->update($request->all());
 
         return redirect('/level')->with('status', 'Data level berhasil diubah!');
     }
 
     public function destroy($id)
     {
         $check = LevelModel::find($id);
         if (!$check) {
             return redirect('/level')->with('error', 'Data user tidak ditemukan');
         }
 
         try {
             LevelModel::destroy($id);
 
             return redirect('/level')->with('success', 'Data level berhasil dihapus');
         } catch (\Exception $e) {
             return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
         }
    }
    public function create_ajax()
     {
         return view('level.create_ajax');
     }
 
     public function store_ajax(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'level_kode' => 'required|string|min:3|unique:m_level,level_kode',
                 'level_nama' => 'required|string|min:3',
 
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => "Validasi gagal",
                     'msgField' => $validator->errors()
                 ]);
             }
 
             LevelModel::create($request->all());
 
             return response()->json([
                 'status' => true,
                 'message' => 'Data level berhasil disimpan'
             ]);
         }
         redirect('/level');
     }
 
     public function edit_ajax(string $id)
     {
         $level = LevelModel::find($id);
 
         return view('level.edit_ajax')->with('level', $level);
     }
 
     public function update_ajax(Request $request, $id)
     {
         // cek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'level_kode' => 'required|string|min:3',
                 'level_nama' => 'required|string|min:3',
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
             $check = LevelModel::find($id);
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
         $level = LevelModel::find($id);
 
         return view('level.confirm_ajax', [
             'level' => $level
         ]);
     }
 
     public function delete_ajax(Request $request, $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $level = LevelModel::find($id);
 
             if ($level) {
                 $level->delete();
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
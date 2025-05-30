<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LevelModel;
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
                 $btn = '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                 $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level/' . $level->level_id) . '">'
                     . csrf_field() . method_field('DELETE') .
                     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
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
}
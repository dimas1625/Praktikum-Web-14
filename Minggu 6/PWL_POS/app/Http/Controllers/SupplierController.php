<?php
 
 namespace App\Http\Controllers;
 
 use App\Models\SupplierModel;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Validator;
 use Yajra\DataTables\Facades\DataTables;
 
 class SupplierController extends Controller
 {
     public function index()
     {
         $breadcrumb = (object) [
             'title' => 'Daftar Supplier',
             'list' => ['Home', 'Supplier']
         ];
 
         $page = (object) [
             'title' => 'Daftar Supplier yang terdaftar di sistem'
         ];
 
         $activeMenu = 'supplier';
 
         return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
     }
 
     public function list(Request $request)
     {
         $supplier = SupplierModel::select('id', 'supplier_kode', 'supplier_nama', 'supplier_alamat');
 
         if ($request->supplier_nama) {
             $supplier->where('supplier_nama', 'like', '%' . $request->supplier_nama . '%');
         }
 
         return DataTables::of($supplier)
             ->addIndexColumn()
             ->addColumn('aksi', function ($supplier) {
                 // $btn = '<a href="' . url('/supplier/' . $supplier->supplier_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                 // $btn .= '<a href="' . url('/supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                 // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/supplier/' . $supplier->supplier_id) . '">'
                 //     . csrf_field() . method_field('DELETE') .
                 //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                 $btn = '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                     '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                     '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                 $btn .= '<bsutton onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id .
                     '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }
 
     public function create()
     {
         $breadcrumb = (object) [
             'title' => 'Tambah Supplier',
             'list' => ['Home', 'Supplier', 'Tambah']
         ];
 
         $page = (object) [
             'title' => 'Tambah Supplier Baru'
         ];
 
         $activeMenu = 'supplier';
 
         return view('supplier.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
     }
 
     public function store(Request $request)
     {
         $request->validate([
             'supplier_kode' => 'required',
             'supplier_nama' => 'required',
             'supplier_alamat' => 'required'
         ]);
 
         return redirect('/supplier')->with('success', 'Data supplier berhasil ditambahkan');
     }
 
     public function show($id)
     {
         $breadcrumb = (object) [
             'title' => 'Detail Supplier',
             'list' => ['Home', 'Supplier', 'Detail']
         ];
 
         $page = (object) [
             'title' => 'Detail Supplier'
         ];
 
         $activeMenu = 'supplier';
 
         $supplier = SupplierModel::find($id);
 
         return view('supplier.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'supplier' => $supplier]);
     }
 
     public function edit($id)
     {
         $breadcrumb = (object) [
             'title' => 'Edit Supplier',
             'list' => ['Home', 'Supplier', 'Edit']
         ];
 
         $page = (object) [
             'title' => 'Edit Supplier'
         ];
 
         $activeMenu = 'supplier';
 
         $supplier = SupplierModel::find($id);
 
         return view('supplier.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'supplier' => $supplier]);
     }
 
     public function update(Request $request, $id)
     {
         $request->validate([
             'supplier_kode' => 'required|unique:m_supplier',
             'supplier_nama' => 'required',
             'supplier_alamat' => 'required'
         ]);
 
         SupplierModel::find($id)->update($request->all());
 
         return redirect('/supplier')->with('success', 'Data supplier berhasil diubah');
     }
 
     public function destroy($id)
     {
         $check = SupplierModel::find($id);
         if (!$check) {
             return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan');
         }
 
         try {
             SupplierModel::destroy($id);
 
             return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
         } catch (\Exception $e) {
             return redirect('/supplier')->with('error', 'Data supplier gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
         }
     }
     public function create_ajax()
     {
         return view('supplier.create_ajax',);
     }
 
     public function store_ajax(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'supplier_kode' => 'required|string|min:3|unique:m_supplier,supplier_kode',
                 'supplier_nama' => 'required|string|min:3',
                 'supplier_alamat' => 'required|string|min:5'
 
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => "Validasi gagal",
                     'msgField' => $validator->errors()
                 ]);
             }
 
             SupplierModel::create($request->all());
 
             return response()->json([
                 'status' => true,
                 'message' => 'Data supplier berhasil disimpan'
             ]);
         }
         redirect('/supplier');
     }
 
     public function edit_ajax(string $id)
     {
         $supplier = SupplierModel::find($id);
         return view('supplier.edit_ajax', [
             'supplier' => $supplier,
         ]);
     }
 
     public function update_ajax(Request $request, $id)
     {
         // cek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'supplier_kode' => 'required|string|min:3',
                 'supplier_nama' => 'required|string|min:3',
                 'supplier_alamat' => 'required|string|min:3'
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
             $check = SupplierModel::find($id);
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
 
         // return redirect('/');
     }
 
     public function confirm_ajax(string $id)
     {
         $supplier = SupplierModel::find($id);
 
         return view('supplier.confirm_ajax', [
             'supplier' => $supplier
         ]);
     }
 
     public function delete_ajax(Request $request, $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $supplier = SupplierModel::find($id);
 
             if ($supplier) {
                 $supplier->delete();
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
         return redirect('/');
     }
 }
<?php
 
 namespace App\Http\Controllers;
 
 use App\Models\BarangModel;
 use App\Models\KategoriModel;
 use Illuminate\Http\Request;
 use PhpOffice\PhpSpreadsheet\IOFactory;
 use Illuminate\Support\Facades\Validator;
 use Yajra\DataTables\Facades\DataTables;
 
 class BarangController extends Controller
 {
     public function index()
     {
         $breadcrumb = (object) [
             'title' => 'Daftar Barang',
             'list' => ['Home', 'Barang']
         ];
 
         $page = (object) [
             'title' => 'Daftar Barang yang terdaftar di sistem'
         ];
 
         $activeMenu = 'barang';
 
         // $kategori = KategoriModel::all();
 
         // Mengambil semua data kategori untuk filed kategeri_id dan kategori_nama
         $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
 
         return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
     }
 
     public function list(Request $request)
     {
         $barang = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')->with('kategori');
 
          // if ($request->filter_kategori) {
         //     $barang->where('kategori_id', $request->filter_kategori);
         // }
 
         $kategori_id = $request->input('filter_kategori');
         if(!empty($kategori_id)){
             $barang->where('kategori_id', $kategori_id);
         }
 
         return DataTables::of($barang)
             ->addIndexColumn()
             ->addColumn('aksi', function ($barang) {
                 // $btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                 // $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                 // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '">'
                 //     . csrf_field() . method_field('DELETE') .
                 //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                 $btn = '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id .
                     '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id .
                     '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                 $btn .= '<bsutton onclick="modalAction(\'' . url('/barang/' . $barang->barang_id .
                     '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }
 
     public function create()
     {
         $breadcrumb = (object) [
             'title' => 'Tambah Barang',
             'list' => ['Home', 'Barang', 'Tambah']
         ];
 
         $page = (object) [
             'title' => 'Tambah Barang baru'
         ];
 
         $activeMenu = 'barang';
 
         $kategori = KategoriModel::all();
 
         return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
     }
 
     public function store(Request $request)
     {
         $request->validate([
             'kategori_id' => 'required',
             'barang_kode' => 'required|unique:m_barang',
             'barang_nama' => 'required',
             'harga_beli' => 'required',
             'harga_jual' => 'required',
         ]);
 
         BarangModel::create($request->all());
 
         return redirect('/barang')->with('success', 'Data berhasil ditambahkan');
     }
 
     public function show($id)
     {
         $breadcrumb = (object) [
             'title' => 'Detail Barang',
             'list' => ['Home', 'Barang', 'Detail']
         ];
 
         $page = (object) [
             'title' => 'Detail Barang yang terdaftar di sistem'
         ];
 
         $activeMenu = 'barang';
 
         $barang = BarangModel::with('kategori')->find($id);
 
         return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'barang' => $barang]);
     }
 
     public function edit($id)
     {
         $breadcrumb = (object) [
             'title' => 'Edit Barang',
             'list' => ['Home', 'Barang', 'Edit']
         ];
 
         $page = (object) [
             'title' => 'Edit Barang yang terdaftar di sistem'
         ];
 
         $activeMenu = 'barang';
 
         $barang = BarangModel::find($id);
         $kategori = KategoriModel::all();
 
         return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'barang' => $barang, 'kategori' => $kategori]);
     }
 
     public function update(Request $request, $id)
     {
         $request->validate([
             'kategori_id' => 'required',
             'barang_kode' => 'required|unique:m_barang,barang_kode,' . $id . ',barang_id',
             'barang_nama' => 'required',
             'harga_beli' => 'required',
             'harga_jual' => 'required',
         ]);
 
         BarangModel::find($id)->update($request->all());
 
         return redirect('/barang')->with('success', 'Data berhasil diubah');
     }
 
     public function destroy($id)
     {
         $check = BarangModel::find($id);
         if (!$check) {
             return redirect('/user')->with('error', 'Data user tidak ditemukan');
         }
 
         try {
             BarangModel::destroy($id);
 
             return redirect('/user')->with('success', 'Data user berhasil dihapus');
         } catch (\Exception $e) {
             return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
         }
     }
     public function create_ajax()
     {
 
         $kategori = KategoriModel::all();
 
         return view('barang.create_ajax', [
             'kategori' => $kategori
         ]);
     }
 
     public function store_ajax(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'kategori_id' => ['required', 'integer', 'exists:m_kategori,kategori_id'],
                 'barang_kode' => ['required', 'min:3', 'max:20','unique:m_barang,barang_kode'],
                 'barang_nama' => ['required', 'string', 'max:100'],
                 'harga_beli' => ['required', 'numeric'],
                 'harga_jual' => ['required', 'numeric'],
 
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => "Validasi gagal",
                     'msgField' => $validator->errors()
                 ]);
             }
 
             BarangModel::create($request->all());
 
             return response()->json([
                 'status' => true,
                 'message' => 'Data barang berhasil disimpan'
             ]);
         }
         redirect('/barang');
     }
 
     public function edit_ajax(string $id)
     {
         $kategori = KategoriModel::all();
 
         $barang = BarangModel::find($id);
         return view('barang.edit_ajax', [
             'barang' => $barang,
             'kategori' => $kategori
         ]);
     }
 
     public function update_ajax(Request $request, $id)
     {
         // cek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'kategori_id' => ['required', 'integer', 'exists:m_kategori,kategori_id'],
                 'barang_kode' => ['required', 'min:3', 'max:20', 'unique:m_barang,barang_kode, '. $id .',barang_id'],
                 'barang_nama' => ['required', 'string', 'max:100'],
                 'harga_beli' => ['required', 'numeric'],
                 'harga_jual' => ['required', 'numeric'],
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
             $check = BarangModel::find($id);
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
         $barang = BarangModel::find($id);
 
         return view('barang.confirm_ajax', [
             'barang' => $barang
         ]);
     }
 
     public function delete_ajax(Request $request, $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $barang = BarangModel::find($id);
 
             if ($barang) {
                 $barang->delete();
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

     public function import(){
        return view('barang.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                // Validasi file harus xlsx, maksimal 1MB
                'file_barang' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Ambil file dari request
            $file = $request->file('file_barang');

            // Membuat reader untuk file excel dengan format Xlsx
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true); // Hanya membaca data saja

            // Load file excel
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

            // Ambil data excel sebagai array
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];

            // Pastikan data memiliki lebih dari 1 baris (header + data)
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // Baris pertama adalah header, jadi lewati
                        $insert[] = [
                            'kategori_id' => $value['A'],
                            'barang_kode' => $value['B'],
                            'barang_nama' => $value['C'],
                            'harga_beli'  => $value['D'],
                            'harga_jual'  => $value['E'],
                            'created_at'  => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    // Insert data ke database, jika data sudah ada, maka diabaikan
                    BarangModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }

        return redirect('/');
    }
}
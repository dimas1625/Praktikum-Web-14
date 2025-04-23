<?php
 
 namespace App\Http\Controllers;
 
 use Illuminate\Http\Request;
 use App\Models\BarangModel;
 use App\Models\StokModel;
 use App\Models\UserModel;
 use Yajra\DataTables\Facades\DataTables;
 use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\Validator;
 use PhpOffice\PhpSpreadsheet\IOFactory;
 use PhpOffice\PhpSpreadsheet\Spreadsheet;
 use Barryvdh\DomPDF\Facade\Pdf;
 
 class StokController extends Controller
 {
     public function index()
     {
         $breadcrumb = (object) [
             'title' => 'Daftar Stok',
             'list' => ['Home', 'Stok']
         ];
 
         $page = (object) [
             'title' => 'Daftar Stok yang terdaftar dalam sistem'
         ];
 
         $activeMenu = 'stok';
         $barang = BarangModel::all();
         $user = UserModel::whereIn('level_id', [1, 2, 3])->get();
 
         return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'user' => $user, 'activeMenu' => $activeMenu]);
     }
 
     public function list(Request $request)
     {
         $stoks = StokModel::select('stok_id', 'barang_id', 'user_id', 'stok_tanggal_masuk', 'stok_jumlah')
             ->with('barang')
             ->with('user');
 
         if ($request->barang_id) {
             $stoks->where('barang_id', $request->barang_id);
         }
         if ($request->user_id) {
             $stoks->where('user_id', $request->user_id);
         }
 
         return DataTables::of($stoks)
             ->addIndexColumn()
             ->addColumn('aksi', function ($stok) {
                 $btn = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }
 
     public function create_ajax()
     {
         // $barang = BarangModel::select('barang_id', 'barang_nama')->get();
         $barangSudahDipakai = StokModel::pluck('barang_id')->toArray(); // barang_id yang sudah ada di stok
         $barang = BarangModel::whereNotIn('barang_id', $barangSudahDipakai)->get();
         $user = Auth::user(); // ambil user yang login
 
         return view('stok.create_ajax')
             ->with('barang', $barang)
             ->with('user', $user);
     }
 
     public function store_ajax(Request $request)
     {
         // cek apakah request berupa ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'user_id' => 'required|exists:m_user,user_id',
                 'barang_id' => 'required|exists:m_barang,barang_id',
                 'stok_tanggal_masuk' => 'required|date',
                 'stok_jumlah' => 'required|integer'
             ];
 
             // use Illuminate\Support\Facades\Validator;
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false, // response status, false: error/gagal, true: berhasil
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors() // pesan error validasi
                 ]);
             }
 
             StokModel::create($request->all());
 
             return response()->json([
                 'status' => true,
                 'message' => 'Data Stok berhasil disimpan'
             ]);
         }
         return redirect('/');
     }
 
     public function show_ajax(string $id)
     {
         $stok = StokModel::with(['barang', 'user'])->find($id);
 
         return view('stok.show_ajax', ['stok' => $stok]);
     }
 
     public function edit_ajax(string $id)
     {
         $stok = StokModel::find($id);
         $barang = BarangModel::select('barang_id', 'barang_nama')->get();
         $user = Auth::user();
 
         return view('stok.edit_ajax', ['stok' => $stok, 'barang' => $barang, 'user' => $user]);
     }
 
     public function update_ajax(Request $request, $id)
     {
         // cek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'user_id' => 'required|exists:m_user,user_id',
                 'stok_tanggal_masuk' => 'required|date',
                 'stok_jumlah' => 'required|integer'
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
 
             try {
                 StokModel::find($id)->update($request->all());
                 return response()->json([
                     'status' => true,
                     'message' => 'Data berhasil diupdate'
                 ]);
             } catch (\Illuminate\Database\QueryException $e) {
                 return response()->json([
                     'status' => false, // respon json, true: berhasil, false: gagal
                     'message' => 'Validasi gagal.',
                     'msgField' => $validator->errors() // menunjukkan field mana yang error
                 ]);
             }
         }
         return redirect('/');
     }
 
     public function confirm_ajax(string $id)
     {
         $stok = StokModel::find($id);
 
         return view('stok.confirm_ajax', ['stok' => $stok]);
     }
 
     public function delete_ajax(Request $request, $id)
     {
         // cek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $stok = StokModel::find($id);
             if ($stok) {
                 try {
                     $stok->delete();
                     return response()->json([
                         'status' => true,
                         'message' => 'Data berhasil dihapus'
                     ]);
                 } catch (\Illuminate\Database\QueryException $e) {
                     return response()->json([
                         'status' => false,
                         'message' => 'Data tidak bisa dihapus'
                     ]);
                 }
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data tidak ditemukan'
                 ]);
             }
         }
         return redirect('/');
     }
 
     public function export_pdf()
     {
         $stok = StokModel::select('stok_id', 'barang_id', 'user_id', 'stok_tanggal_masuk', 'stok_jumlah')
             ->orderBy('stok_id')
             ->with('barang')
             ->with('user')
             ->get();
 
         $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);
         $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
         $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
         $pdf->render();
 
         return $pdf->stream('Data Stok ' . date('Y-m-d H:i:s') . '.pdf');
     }
 
     public function import()
     {
         return view('stok.import');
     }
 
     public function import_ajax(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'file_stok' => ['required', 'mimes:xlsx', 'max:1024']
             ];
 
             $validator = Validator::make($request->all(), $rules);
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors()
                 ]);
             }
 
             $file = $request->file('file_stok');
 
             $reader = IOFactory::createReader('Xlsx');
             $reader->setReadDataOnly(true);
             $spreadsheet = $reader->load($file->getRealPath());
             $sheet = $spreadsheet->getActiveSheet();
 
             $data = $sheet->toArray(null, false, true, true);
 
             $insert = [];
             if (count($data) > 1) {
                 foreach ($data as $row => $value) {
                     if ($row > 1) {
                         $insert[] = [
                             'barang_id' => $value['A'],
                             'user_id' => $value['B'],
                             'stok_tanggal_masuk' => $value['C'],
                             'stok_jumlah' => $value['D'],
                             'created_at' => now(),
                             'updated_at' => now(),
                         ];
                     }
                 }
 
                 if (count($insert) > 0) {
                     StokModel::insertOrIgnore($insert);
                 }
 
                 return response()->json([
                     'status' => true,
                     'message' => 'Data stok berhasil diimport'
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Tidak ada data yang diimport'
                 ]);
             }
         }
 
         return redirect('/');
     }
 
     public function export_excel()
     {
         $stok = StokModel::with(['barang', 'user'])
             ->orderBy('stok_tanggal_masuk')
             ->get();
 
         $spreadsheet = new Spreadsheet();
         $sheet = $spreadsheet->getActiveSheet();
 
         $sheet->setCellValue('A1', 'No');
         $sheet->setCellValue('B1', 'Nama Barang');
         $sheet->setCellValue('C1', 'User Input');
         $sheet->setCellValue('D1', 'Tanggal Stok');
         $sheet->setCellValue('E1', 'Jumlah Stok');
 
         $sheet->getStyle('A1:E1')->getFont()->setBold(true);
 
         $no = 1;
         $baris = 2;
         foreach ($stok as $item) {
             $sheet->setCellValue('A' . $baris, $no);
             $sheet->setCellValue('B' . $baris, $item->barang->barang_nama ?? '-');
             $sheet->setCellValue('C' . $baris, $item->user->nama ?? '-');
             $sheet->setCellValue('D' . $baris, $item->stok_tanggal_masuk);
             $sheet->setCellValue('E' . $baris, $item->stok_jumlah);
             $baris++;
             $no++;
         }
 
         foreach (range('A', 'E') as $columnID) {
             $sheet->getColumnDimension($columnID)->setAutoSize(true);
         }
 
         $sheet->setTitle('Data Stok');
 
         $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
         $filename = 'Data_Stok_' . date('Y-m-d_H-i-s') . '.xlsx';
 
         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
         header('Content-Disposition: attachment;filename="' . $filename . '"');
         header('Cache-Control: max-age=0');
         header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
         header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
         header('Cache-Control: cache, must-revalidate');
         header('Pragma: public');
 
         $writer->save('php://output');
         exit;
     }
 }
<?php
 
 namespace App\Http\Controllers;
 
 use Illuminate\Http\Request;
 use App\Models\BarangModel;
 use App\Models\PenjualanModel;
 use App\Models\StokModel;
 use App\Models\UserModel;
 use App\Models\PenjualanDetailModel;
 use Illuminate\Support\Facades\Auth;
 use Yajra\DataTables\Facades\DataTables;
 use Illuminate\Support\Facades\Validator;
 use Illuminate\Support\Facades\DB;
 use Barryvdh\DomPDF\Facade\Pdf;
 
 class PenjualanController extends Controller
 {
     public function index()
     {
         $breadcrumb = (object) [
             'title' => 'Daftar Penjualan',
             'list' => ['Home', 'Penjualan']
         ];
 
         $page = (object) [
             'title' => 'Daftar Penjualan yang terdaftar dalam sistem'
         ];
 
         $activeMenu = 'penjualan';
         $user = UserModel::whereIn('level_id', [1, 2, 3])->get();
 
         return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
     }
 
     public function list(Request $request)
     {
         $penjualans = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'tanggal_penjualan')
             ->with('user');
 
         if ($request->user_id) {
             $penjualans->where('user_id', $request->user_id);
         }
 
         return DataTables::of($penjualans)
             ->addIndexColumn()
             ->addColumn('aksi', function ($penjualan) {
                 $btn = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                 // $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }
 
     public function create_ajax()
     {
         $barang = DB::table('m_barang')
             ->join('t_stok', 'm_barang.barang_id', '=', 't_stok.barang_id')
             ->where('t_stok.stok_jumlah', '>', 0)
             ->select('m_barang.*', 't_stok.stok_jumlah')
             ->get(); // cuma barang yang ada stoknya yang ditampilin
 
         $user = Auth::user(); // ambil user yang login
         $kode = 'PJ-' .  PenjualanModel::orderBy('penjualan_id', 'desc')->value('penjualan_id') + 1;
 
         return view('penjualan.create_ajax')
             ->with('barang', $barang)
             ->with('user', $user)
             ->with('kode', $kode);
     }
 
     public function store_ajax(Request $request)
     {
         // cek apakah request berupa ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'user_id' => 'required|exists:m_user,user_id',
                 'pembeli' => 'required|max:40',
                 'penjualan_kode' => 'required|max:20',
                 'tanggal_penjualan' => 'required|date',
                 'barang_id' => 'required|array',
                 'harga' => 'required|array',
                 'jumlah' => 'required|array'
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
 
             try {
                 DB::beginTransaction();
 
                 // Simpan ke t_penjualan
                 $penjualan = PenjualanModel::create([
                     'user_id' => $request->user_id,
                     'pembeli' => $request->pembeli,
                     'penjualan_kode' => $request->penjualan_kode,
                     'tanggal_penjualan' => $request->tanggal_penjualan,
                 ]);
 
                 // Loop dan simpan ke t_penjualan_detail
                 foreach ($request->barang_id as $i => $barang_id) {
                     PenjualanDetailModel::create([
                         'penjualan_id' => $penjualan->penjualan_id,
                         'barang_id' => $barang_id,
                         'harga' => $request->harga[$i],
                         'jumlah' => $request->jumlah[$i],
                     ]);
 
                     // kurangi stok
                     $stok = StokModel::where('barang_id', $barang_id)->first();
                     if ($stok) {
                         if ($stok->stok_jumlah >= $request->jumlah[$i]) {
                             $stok->stok_jumlah -= $request->jumlah[$i];
                             $stok->save();
                         } else {
                             // rollback jika stok tidak mencukupi
                             DB::rollBack();
                             return response()->json([
                                 'status' => false,
                                 'message' => 'Stok barang ' . $stok->barang->barang_nama . ' tidak mencukupi'
                             ]);
                         }
                     } else {
                         // rollback jika stok tidak ditemukan
                         DB::rollBack();
                         return response()->json([
                             'status' => false,
                             'message' => 'Stok untuk barang tidak ditemukan'
                         ]);
                     }
                 }
 
                 DB::commit();
 
                 return response()->json([
                     'status' => true,
                     'message' => 'Data Penjualan dan Detail berhasil disimpan',
                     'struk_url' => url('/penjualan/struk/' . $penjualan->penjualan_id)
                 ]);
             } catch (\Exception $e) {
                 DB::rollback();
 
                 return response()->json([
                     'status' => false,
                     'message' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()
                 ]);
             }
         }
         return redirect('/');
     }
 
     public function show_ajax(string $id)
     {
         $penjualan = PenjualanModel::with(['user'])->find($id);
         $detail = PenjualanDetailModel::where('penjualan_id', $penjualan->id)->get();
 
         return view('penjualan.show_ajax', ['penjualan' => $penjualan, 'detail' => $detail]);
     }

     public function confirm_ajax(string $id)
     {
         $penjualan = PenjualanModel::find($id);
         $penjualanDetail = PenjualanDetailModel::find($penjualan->penjualan_id);
 
         return view('penjualan.confirm_ajax', ['penjualan' => $penjualan, 'penjualanDetail' => $penjualanDetail]);
     }
 
     public function delete_ajax(Request $request, $id)
     {
         // cek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $penjualan = PenjualanModel::findOrFail($id)->delete();
             if ($penjualan) {
                 try {
                     // Hapus dulu semua detail yang terkait
                     $penjualan->detail()->delete(); // Pastikan relasi 'detail' ada di model
                     // Baru hapus data induknya
                     $penjualan->delete();
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
         $penjualan = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'tanggal_penjualan')
             ->with('user')
             ->get();
 
         $detail = PenjualanDetailModel::all();
 
         $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan, 'detail' => $detail]);
         $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
         $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
         $pdf->render();
 
         return $pdf->stream('Data Penjualan ' . date('Y-m-d H:i:s') . '.pdf');
     }
 
     public function struk($id)
     {
         $penjualan = PenjualanModel::with(['detail.barang', 'user'])->findOrFail($id);
         return view('penjualan.struk', compact('penjualan'));
     }
 }
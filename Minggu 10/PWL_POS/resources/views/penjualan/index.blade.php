@extends('layouts.template')
 
 @section('content')
     <div class="card card-outline card-primary">
         <div class="card-header">
             <h3 class="card-title">{{ $page->title }}</h3>
             <div class="card-tools">
                 {{-- <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-sm btn-info">Import Barang</button>  --}}
                 {{-- <a href="{{ url('/stok/export_excel') }}" class="btn btn-sm btn-primary"><i class="fa fa-file-excel"></i> Export Barang</a> --}}
                 <a href="{{ url('/penjualan/export_pdf') }}" class="btn btn-sm btn-warning""><i
                         class="fa fa-file-pdf"></i> Export Penjualan (PDF)</a>
                 <button onclick="modalAction('{{ url('penjualan/create_ajax') }}')" class="btn btn-sm btn-success">
                     Tambah Data
                 </button>
             </div>
         </div>
         <div class="card-body">
             <!-- untuk Filter data -->
             <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                 <div class="row">
                     <div class="col-md-12">
                         <div class="form-group row">
                             <label class="col-1 control-label col-form-label">Filter:</label>
                             <div class="col-3">
                                 <select class="form-control" id="user_id" name="user_id" required>
                                     <option value="">- Semua -</option>
                                     @foreach ($user as $item)
                                         <option value="{{ $item->user_id }}">{{ $item->nama }}</option>
                                     @endforeach
                                 </select>
                                 <small class="form-text text-muted">List User</small>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             @if (session('success'))
                 <div class="alert alert-success">{{ session('success') }}</div>
             @endif
             @if (session('error'))
                 <div class="alert alert-danger">{{ session('error') }}</div>
             @endif
             <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
                 <thead>
                     <tr>
                         <th>No</th>
                         <th>Kode Penjualan</th>
                         <th>Penginput</th>
                         <th>Nama Pembeli</th>
                         <th>Tanggal</th>
                         <th>Aksi</th>
                     </tr>
                 </thead>
             </table>
         </div>
     </div>
 
     <!-- Modal Struk -->
     <div class="modal fade" id="modalStruk" tabindex="-1" aria-hidden="true">
         <div class="modal-dialog modal-sm">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title">Struk Penjualan</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                 </div>
                 <div class="modal-body" id="modalStrukContent">
                     <!-- struk AJAX -->
                 </div>
                 <div class="modal-footer">
                     <button onclick="printStruk()" class="btn btn-primary">Cetak</button>
                 </div>
             </div>
         </div>
     </div>
 
     <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
         data-keyboard="false" data-width="75%" aria-hidden="true"></div>
 @endsection
 
 @push('css')
 @endpush
 
 @push('js')
     <script>
         function modalAction(url = '') {
             $('#myModal').load(url, function() {
                 $('#myModal').modal('show');
             });
         }
 
         var dataPenjualan;
         $(document).ready(function() {
             dataPenjualan = $('#table_stok').DataTable({
                 serverSide: true,
                 ajax: {
                     "url": "{{ url('penjualan/list') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data": function(d) {
                         d.user_id = $('#user_id').val();
                     }
                 },
                 columns: [{
                         data: "DT_RowIndex",
                         className: "text-center",
                         orderable: false,
                         searchable: false
                     },
                     {
                         data: "penjualan_kode",
                         className: "",
                         orderable: true,
                         searchable: true
                     },
                     {
                         data: "user.nama",
                         className: "",
                         orderable: true,
                         searchable: true
                     },
                     {
                         data: "pembeli",
                         className: "",
                         orderable: true,
                         searchable: true
                     },
                     {
                         data: "tanggal_penjualan",
                         className: "",
                         orderable: true,
                         searchable: true
                     },
                     {
                         data: "aksi",
                         className: "",
                         orderable: false,
                         searchable: false
                     }
                 ]
             });
 
             $('#table-barang_filter input').unbind().bind().on('keyup', function(e) {
                 if (e.keyCode == 13) { // enter key 
                     dataPenjualan.search(this.value).draw();
                 }
             });
 
 
         });
 
         $('#user_id').on('change', function() {
             dataPenjualan.ajax.reload();
         });
 
         function printStruk() {
             var strukContent = document.getElementById('modalStrukContent').innerHTML;
 
             var printWindow = window.open('', '_blank');
             printWindow.document.write(`
                 <html>
                 <head>
                     <title>Struk Penjualan</title>
                     <style>
                         body { font-family: monospace; padding: 10px; }
                         .center { text-align: center; }
                         table { width: 100%; border-collapse: collapse; }
                         th, td { padding: 4px; text-align: left; font-size: 12px; }
                         .footer { margin-top: 10px; text-align: center; font-size: 12px; }
                         .total { font-weight: bold; font-size: 14px; margin-top: 10px; }
                     </style>
                 </head>
                 <body>
                     ${strukContent}
                 </body>
                 </html>
             `);
             printWindow.document.close();
         }
 
         function showStruk(strukUrl) {
             console.log("Struk URL: ", strukUrl);
             // Memuat konten struk ke dalam modal menggunakan AJAX
             $.get(strukUrl, function(data) {
                 console.log("Data struk: ", data);
                 $('#modalStrukContent').html(data); // Isi modal dengan data struk
                 console.log('Menampilkan modalStruk');
                 $('#modalStruk').modal('show'); // Tampilkan modal
             }).fail(function() {
                 Swal.fire({
                     icon: 'error',
                     title: 'Terjadi Kesalahan',
                     text: 'Tidak dapat memuat struk.'
                 });
             });
         }
     </script>
 @endpush
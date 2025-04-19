@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Ketegori</h3>
        <div class="card-tools">

            <button onclick="modalAction('{{ url('/kategori/import') }}')" class="btn btn-info">Import Kategori</button>
            <a href="{{ url('kategori/export_excel') }}" class="btn btn-primary">Export Kategori</a>
            <button onclick="modalAction('{{ url('kategori/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>

        </div>
    </div>
 <div class="card-body">
     @if(session('error'))
         <div class="alert alert-danger">
             {{ session('error') }}
         </div>
     @endif

     @if(session('success'))
         <div class="alert alert-success">
             {{ session('success') }}
         </div>
     @endif
     <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
         <thead>
             <tr>
                 <th>ID</th>
                 <th>Kode Kategori</th>
                 <th>Nama Kategori</th>
                 <th>Aksi</th>
             </tr>
         </thead>
     </table>
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
 $('#myModal').load(url, function () {
     $('#myModal').modal('show');
 });
}
var dataKategori;
 $(document).ready(function () {
      dataKategori = $('#table_kategori').DataTable({
         // serverSide: true, jika ingin menggunakan server side processing
         processing: true,
                     serverSide: true,
                     ajax: {
                         "url": "{{ url('kategori/list') }}",
                         "dataType": "json",
                         "type": "POST",
                         "data": function (d) {
                             d.filter_kategori = $('.filter_kategori').val();
                         }
                     },
                  columns: [
                      // nomor urut dari laravel datatable addIndexColumn()
                      {
                          data: 'DT_RowIndex',
                          className: 'text-center',
                          width: "5%",
                          orderable: false,
                          searchable: false
                      },
                      {
                          data: 'kategori_kode',
                          className: '',
                          width: "10%",
                          orderable: true,
                          searchable: true
                        }, 
                     {
                         data: "kategori_nama",
                         className: "",
                         width: "14%",
                         orderable: true,
                         searchable: false
                     }, {
                         data: "aksi",
                         className: "text-center",
                         width: "14%",
                         orderable: false,
                         searchable: false
                     }
                     ]
                 });
                 $('#table_kategori_filter input').unbind().bind().on('keyup', function (e) {
                     if (e.keyCode == 13) { // enter key
                         dataKategori.search(this.value).draw();
                     }
                 });
             });
         </script>
  @endpush

@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/user/import') }}')" class="btn btn-sm btn-info mt-1">Import User</button>
            <a href="{{ url('user/export_excel') }}" class="btn btn-primary">Export User</a>
            <a href="{{ url('user/export_pdf') }}" class="btn btn-warning"><i class="fa fa-filepdf"></i> Export User (PDF)</a>
            <button onclick="modalAction('{{ url('user/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah AJAX</button>
        </div>
    </div>
    <div class="card-body">
        {{-- Pastikan session key sama dengan yang di-controller: 'success' dan 'error' --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter :</label>
                    <div class="col-3">
                        <select class="form-control" id="level_id" name="level_id" required>
                            <option value="">- Semua -</option>
                            @foreach($level as $item)
                                <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Level Pengguna</small>
                    </div>
                </div>
            </div>
        </div>        

        <table class="table table-bordered table-striped table-hover table-sm" id="table_user">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Level Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Modal --}}
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog"
     data-backdrop="static" data-keyboard="false" aria-hidden="true">
</div>
@endsection

@push('css')
<!-- Tambahkan CSS khusus jika perlu -->
@endpush

@push('js')
<script>
// Pastikan setiap Ajax request mengirimkan CSRF Token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Fungsi untuk menampilkan modal
function modalAction(url = '') {
    $('#myModal').load(url, function () {
        $('#myModal').modal('show');
    });
}

var dataUser;
$(document).ready(function() {
    dataUser = $('#table_user').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('user/list') }}",
            dataType: "json",
            type: "POST",
            data: function(d) {
                d.level_id = $('#level_id').val();
            }
        },
        columns: [
            {
                // Menampilkan nomor urut (pastikan server mengirimkan DT_RowIndex)
                data: "DT_RowIndex",
                className: "text-center",
                orderable: false,
                searchable: false
            },
            {
                data: "username",
                orderable: true,
                searchable: true
            },
            {
                data: "nama",
                orderable: true,
                searchable: true
            },
            {
                // Menampilkan nama level dari relasi (pastikan query server mengembalikan data level dengan key level_nama)
                data: "level.level_nama",
                orderable: false,
                searchable: false
            },
            {
                data: "aksi",
                orderable: false,
                searchable: false
            }
        ]
    });
    
    // Trigger reload saat filter level berubah
    $('#level_id').on('change', function() {
         dataUser.ajax.reload();
    });

    // Pencarian via keyboard (tekan tombol Enter)
    $('#table_user_filter input').unbind().bind('keyup', function(e) {
         if(e.keyCode == 13) { // enter key
             dataUser.search(this.value).draw();
         }
    });
});
</script>
@endpush

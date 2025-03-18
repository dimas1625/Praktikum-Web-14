@extends('adminlte::page')

{{-- Set judul halaman --}}
@section('title', 'Dashboard')

{{-- Atur header konten --}}
@section('content_header')
    <h1>
        Home
        <small>Welcome</small>
    </h1>
@stop

{{-- Atur konten utama --}}
@section('content')
    <p>Welcome to this beautiful admin panel.</p>
@stop

{{-- Push extra CSS --}}
@push('css')
    {{-- Tambahkan stylesheet tambahan jika diperlukan --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra JavaScript --}}
@push('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@endpush

<!DOCTYPE html>
<html>
<head>
    <title>Shoes List</title>
</head>
<body>
    <h1>Daftar Sepatu</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <a href="{{ route('shoes.create') }}">Tambah Sepatu</a>

    <ul>
        @foreach ($shoes as $shoe)
            <li>
                <strong>{{ $shoe->Merk }}</strong> - Warna: {{ $shoe->color }} - Ukuran: {{ $shoe->size }} 
                <a href="{{ route('shoes.show', $shoe) }}">Lihat</a> | 
                <a href="{{ route('shoes.edit', $shoe) }}">Edit</a>

                <form action="{{ route('shoes.destroy', $shoe) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus sepatu ini?')">Hapus</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>

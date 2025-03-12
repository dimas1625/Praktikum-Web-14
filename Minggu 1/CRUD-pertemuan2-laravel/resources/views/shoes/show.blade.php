<!DOCTYPE html>
<html>
<head>
    <title>Detail Sepatu</title>
</head>
<body>
    <h1>Detail Sepatu</h1>

    <p><strong>Merk:</strong> {{ $shoe->brand }}</p>
    <p><strong>Warna:</strong> {{ $shoe->color }}</p>
    <p><strong>Ukuran:</strong> {{ $shoe->size }}</p>

    <a href="{{ route('shoes.index') }}">Kembali ke Daftar</a> |
    <a href="{{ route('shoes.edit', $shoe) }}">Edit Sepatu</a>

    <form action="{{ route('shoes.destroy', $shoe) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus sepatu ini?')">Hapus</button>
    </form>
</body>
</html>

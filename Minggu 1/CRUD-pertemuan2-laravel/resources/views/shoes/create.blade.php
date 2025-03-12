<!DOCTYPE html>
<html>
<head>
    <title>Tambah Sepatu</title>
</head>
<body>
    <h1>Tambah Sepatu</h1>

    <form action="{{ route('shoes.store') }}" method="POST">
        @csrf
        <label for="Merk">Merk:</label>
        <input type="text" name="Merk" required>
        <br>

        <label for="color">Warna:</label>
        <input type="text" name="color" required>
        <br>

        <label for="size">Ukuran:</label>
        <input type="number" name="size" required min="1">
        <br>

        <button type="submit">Tambah Sepatu</button>
    </form>


    <a href="{{ route('shoes.index') }}">Kembali ke Daftar</a> //Fungsi ini mencari rute yang telah dinamai shoes.index dan mengembalikan URL lengkap untuk rute tersebut. 
</body>
</html>

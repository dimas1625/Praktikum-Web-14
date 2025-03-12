<!DOCTYPE html>
<html>
<head>
    <title>Edit Sepatu</title>
</head>
<body>
    <h1>Edit Sepatu</h1>

    <form action="{{ route('shoes.update', $shoe) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="Merk">Merk:</label>
        <input type="text" name="Merk" value="{{ $shoe->Merk }}" required>
        <br>

        <label for="color">Warna:</label>
        <input type="text" name="color" value="{{ $shoe->color }}" required>
        <br>

        <label for="size">Ukuran:</label>
        <input type="number" name="size" value="{{ $shoe->size }}" required min="1">
        <br>

        <button type="submit">Perbarui Sepatu</button>
    </form>

    <a href="{{ route('shoes.index') }}">Kembali ke Daftar</a>
</body>
</html>

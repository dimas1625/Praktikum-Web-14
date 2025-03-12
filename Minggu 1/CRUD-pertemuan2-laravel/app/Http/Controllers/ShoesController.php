<?php
namespace App\Http\Controllers;

use App\Models\Shoes;
use Illuminate\Http\Request;

class ShoesController extends Controller
{
    // Menampilkan daftar sepatu
    public function index()
    {
        $shoes = Shoes::all();
        return view('shoes.index', compact('shoes'));
    }

    // Menampilkan form tambah sepatu
    public function create()
    {
        return view('shoes.create');
    }

    // Menyimpan sepatu baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'Merk' => 'required',
            'color' => 'required',
            'size' => 'required|integer',
        ]);

        // Hanya menyimpan atribut yang diizinkan
        Shoes::create($request->all());
        return redirect()->route('shoes.index')->with('success', 'Sepatu berhasil ditambahkan.');
    }

    // Menampilkan detail sepatu tertentu
    public function show(Shoes $shoe)
    {
        return view('shoes.show', compact('shoe'));
    }

    // Menampilkan form edit sepatu
    public function edit(Shoes $shoe)
    {
        return view('shoes.edit', compact('shoe'));
    }

    // Memperbarui data sepatu
    public function update(Request $request, Shoes $shoe)
    {
        $request->validate([
            'Merk' => 'required',
            'color' => 'required',
            'size' => 'required|integer',
        ]);

        // Hanya menyimpan atribut yang diizinkan
        $shoe->update($request->only(['Merk', 'color', 'size']));
        return redirect()->route('shoes.index')->with('success', 'Shoes updated successfully.');
    }

    // Menghapus sepatu dari database
    public function destroy(Shoes $shoe)
    {
        $shoe->delete();
        return redirect()->route('shoes.index')->with('success', 'Shoes deleted successfully.');
    }
}

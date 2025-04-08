<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kategori_id' => 1, 'barang_kode' => 'ELEK001', 'barang_nama' => 'TV LED 32 Inch', 'harga_beli' => 2000000, 'harga_jual' => 2500000],
            ['kategori_id' => 1, 'barang_kode' => 'ELEK002', 'barang_nama' => 'Laptop Core i5', 'harga_beli' => 7000000, 'harga_jual' => 8500000],
            ['kategori_id' => 2, 'barang_kode' => 'PAK001', 'barang_nama' => 'Kaos Polos', 'harga_beli' => 50000, 'harga_jual' => 75000],
            ['kategori_id' => 2, 'barang_kode' => 'PAK002', 'barang_nama' => 'Jaket Kulit', 'harga_beli' => 350000, 'harga_jual' => 500000],
            ['kategori_id' => 3, 'barang_kode' => 'MAK001', 'barang_nama' => 'Roti Tawar', 'harga_beli' => 10000, 'harga_jual' => 15000],
            ['kategori_id' => 3, 'barang_kode' => 'MAK002', 'barang_nama' => 'Kue Brownies', 'harga_beli' => 25000, 'harga_jual' => 35000],
            ['kategori_id' => 4, 'barang_kode' => 'MIN001', 'barang_nama' => 'Teh Botol', 'harga_beli' => 5000, 'harga_jual' => 8000],
            ['kategori_id' => 4, 'barang_kode' => 'MIN002', 'barang_nama' => 'Kopi Sachet', 'harga_beli' => 2000, 'harga_jual' => 4000],
            ['kategori_id' => 5, 'barang_kode' => 'PRT001', 'barang_nama' => 'Panci Stainless', 'harga_beli' => 120000, 'harga_jual' => 150000],
            ['kategori_id' => 5, 'barang_kode' => 'PRT002', 'barang_nama' => 'Sapu Lidi', 'harga_beli' => 15000, 'harga_jual' => 25000],
        ];

        DB::table('m_barang')->insert($data);
    }
}

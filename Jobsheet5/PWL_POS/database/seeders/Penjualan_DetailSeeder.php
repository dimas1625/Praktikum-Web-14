<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanDetailSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now(); // Deklarasikan variabel $now

        DB::table('t_penjualan_detail')->insert([
            // Transaksi 1
            [
                'penjualan_id' => 1,
                'barang_id'    => 1,
                'harga'        => 50000,
                'jumlah'       => 2,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 1,
                'barang_id'    => 2,
                'harga'        => 30000,
                'jumlah'       => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 1,
                'barang_id'    => 3,
                'harga'        => 15000,
                'jumlah'       => 3,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Transaksi 2
            [
                'penjualan_id' => 2,
                'barang_id'    => 4,
                'harga'        => 40000,
                'jumlah'       => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 2,
                'barang_id'    => 5,
                'harga'        => 20000,
                'jumlah'       => 2,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 2,
                'barang_id'    => 6,
                'harga'        => 10000,
                'jumlah'       => 5,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Transaksi 3
            [
                'penjualan_id' => 3,
                'barang_id'    => 7,
                'harga'        => 25000,
                'jumlah'       => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 3,
                'barang_id'    => 8,
                'harga'        => 35000,
                'jumlah'       => 2,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 3,
                'barang_id'    => 9,
                'harga'        => 5000,
                'jumlah'       => 4,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Transaksi 4
            [
                'penjualan_id' => 4,
                'barang_id'    => 10,
                'harga'        => 80000,
                'jumlah'       => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 4,
                'barang_id'    => 1,
                'harga'        => 50000,
                'jumlah'       => 2,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 4,
                'barang_id'    => 2,
                'harga'        => 30000,
                'jumlah'       => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Transaksi 5
            [
                'penjualan_id' => 5,
                'barang_id'    => 3,
                'harga'        => 15000,
                'jumlah'       => 3,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 5,
                'barang_id'    => 4,
                'harga'        => 40000,
                'jumlah'       => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 5,
                'barang_id'    => 5,
                'harga'        => 20000,
                'jumlah'       => 2,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Transaksi 6
            [
                'penjualan_id' => 6,
                'barang_id'    => 6,
                'harga'        => 10000,
                'jumlah'       => 5,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 6,
                'barang_id'    => 7,
                'harga'        => 25000,
                'jumlah'       => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 6,
                'barang_id'    => 8,
                'harga'        => 35000,
                'jumlah'       => 2,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Transaksi 7
            [
                'penjualan_id' => 7,
                'barang_id'    => 9,
                'harga'        => 5000,
                'jumlah'       => 4,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 7,
                'barang_id'    => 10,
                'harga'        => 80000,
                'jumlah'       => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 7,
                'barang_id'    => 1,
                'harga'        => 50000,
                'jumlah'       => 2,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Transaksi 8
            [
                'penjualan_id' => 8,
                'barang_id'    => 2,
                'harga'        => 30000,
                'jumlah'       => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 8,
                'barang_id'    => 3,
                'harga'        => 15000,
                'jumlah'       => 3,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 8,
                'barang_id'    => 4,
                'harga'        => 40000,
                'jumlah'       => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Transaksi 9
            [
                'penjualan_id' => 9,
                'barang_id'    => 5,
                'harga'        => 20000,
                'jumlah'       => 2,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 9,
                'barang_id'    => 6,
                'harga'        => 10000,
                'jumlah'       => 5,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 9,
                'barang_id'    => 7,
                'harga'        => 25000,
                'jumlah'       => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Transaksi 10
            [
                'penjualan_id' => 10,
                'barang_id'    => 8,
                'harga'        => 35000,
                'jumlah'       => 2,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 10,
                'barang_id'    => 9,
                'harga'        => 5000,
                'jumlah'       => 4,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'penjualan_id' => 10,
                'barang_id'    => 10,
                'harga'        => 80000,
                'jumlah'       => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ]);
    }
}

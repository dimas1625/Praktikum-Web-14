<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        DB::table('m_supplier')->insert([
            ['supplier_kode' => 'SPL001', 'supplier_nama' => 'Supplier A', 'supplier_alamat' => 'Jakarta'],
            ['supplier_kode' => 'SPL002', 'supplier_nama' => 'Supplier B', 'supplier_alamat' => 'Bandung'],
            ['supplier_kode' => 'SPL003', 'supplier_nama' => 'Supplier C', 'supplier_alamat' => 'Surabaya'],
        ]);
    }
}

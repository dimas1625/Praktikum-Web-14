<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_barang', function (Blueprint $table) {
            $table->id('barang_id'); // Primary key dengan nama barang_id (bigint unsigned)
            $table->unsignedBigInteger('kategori_id'); // kategori_id: bigint(20) unsigned (bisa juga dijadikan foreign key jika ada tabel kategori)
            $table->string('barang_kode', 10); // barang_kode: varchar(10)
            $table->string('barang_nama', 100); // barang_nama: varchar(100)
            $table->integer('harga_beli'); // harga_beli: int(11)
            $table->integer('harga_jual'); // harga_jual: int(11)
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_barang');
    }
};

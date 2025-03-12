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
        Schema::create('t_penjualan_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('penjualan_id'); // Foreign key: penjualan_id (bigint(20) unsigned)
            $table->unsignedBigInteger('barang_id'); // Foreign key: barang_id (bigint(20) unsigned)
            $table->integer('harga'); // harga: int(11)
            $table->integer('jumlah'); // jumlah: int(11)
            $table->timestamps(); // created_at dan updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_penjualan_detail');
    }
};

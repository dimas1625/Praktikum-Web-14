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
        Schema::create('t_penjualan', function (Blueprint $table) {
            $table->id('penjualan_id'); // Primary key: penjualan_id (bigint(20) unsigned)
            $table->unsignedBigInteger('user_id'); // user_id: bigint(20) unsigned (bisa dijadikan foreign key ke tabel users jika diperlukan)
            $table->string('pembeli', 50); // e pembeli: varchar(50)
            $table->string('penjualan_kode', 20); // penjualan_kode: varchar(20)
            $table->dateTime('penjualan_tanggal'); // penjualan_tanggal: datetime
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_penjualan');
    }
};

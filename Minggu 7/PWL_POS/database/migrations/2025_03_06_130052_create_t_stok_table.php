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
        Schema::create('t_stok', function (Blueprint $table) {
            $table->id('stok_id'); // Primary key: stok_id (bigint(20) unsigned)
            $table->unsignedBigInteger('barang_id');  // barang_id: bigint(20) unsigned
            $table->unsignedBigInteger('user_id'); // user_id: bigint(20) unsigned
            $table->dateTime('stok_tanggal'); // stok_tanggal: datetime
            $table->integer('stok_jumlah'); // stok_jumlah: int(11)
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_stok');
    }
};

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
        Schema::create('m_kategori', function (Blueprint $table) {
            // Primary key dengan nama kategori_id (bigint unsigned)
            $table->id('kategori_id');           
            // Kolom kategori_kode: varchar(10)
            $table->string('kategori_kode', 10);
            // Kolom kategori_nama: varchar(100)
            $table->string('kategori_nama', 100);
            // Kolom created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_kategori');
    }
};

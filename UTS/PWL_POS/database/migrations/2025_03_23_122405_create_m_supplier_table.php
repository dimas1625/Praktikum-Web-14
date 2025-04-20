<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('m_supplier', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_kode', 50)->unique(); // Kode unik untuk supplier
            $table->string('supplier_nama', 255); // Nama supplier
            $table->text('supplier_alamat')->nullable(); // Alamat bisa kosong
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_supplier');
    }
};

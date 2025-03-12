<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void //Method ini digunakan untuk menerapkan perubahan pada skema database.
    {
        Schema::create('shoes', function (Blueprint $table) {
            $table->id(); //id digunakan untuk memberikan nomer secara otomatis
            $table->string('Merk'); //kolom ini digunakan untuk menyimpan merk sepatu
            $table->string('color'); //kolom ini digunakan untuk menyimpan warna sepatu
            $table->integer('size'); //kolom ini digunakan untuk menyimpan ukuran sepatu
            $table->timestamps(); //digunakan untuk memberikan 2 kolom yang menyimpan waktu pembaruan data & menyimpan waktu terakhir pembaruan data
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void //Method ini digunakan untuk membatalkan atau mengembalikan perubahan 
    {
        Schema::dropIfExists('shoes');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Nonaktifkan pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus tabel 'responses' jika ada
        Schema::dropIfExists('responses');
        
        // Aktifkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika perlu, tambahkan kode untuk membuat ulang tabel 'responses' di sini
    }
};

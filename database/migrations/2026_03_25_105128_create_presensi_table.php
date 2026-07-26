<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('presensi', function (Blueprint $table) {
        $table->id('id_presensi');
        $table->date('tanggal');

        // TAMBAHKAN KOLOM INI
        // Pakai integer karena isinya angka jam (1, 2, 3, dst)
        $table->enum('jam_pelajaran', [
    'Jam ke-1', 
    'Jam ke-2', 
    'Jam ke-3', 
    'Jam ke-4', 
    'Jam ke-5', 
    'Jam ke-6', 'Jam ke-7','Jam ke-8'])->nullable();

        $table->dateTime('waktu_dibuka');
        $table->dateTime('waktu_ditutup');
        $table->enum('status_sesi', ['dibuka', 'ditutup'])->default('ditutup');

        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('created_by')
              ->references('id_user') 
              ->on('users')
              ->nullOnDelete();

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
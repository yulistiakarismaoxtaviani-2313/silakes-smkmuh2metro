<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_presensi', function (Blueprint $table) {
            // Primary Key
            $table->bigIncrements('id_detail_presensi');

            // Foreign Key ke tabel presensi (Header)
            $table->unsignedBigInteger('id_presensi');
            $table->foreign('id_presensi')
                  ->references('id_presensi')
                  ->on('presensi')
                  ->onDelete('cascade');

            // Foreign Key ke tabel siswa
            $table->unsignedBigInteger('id_siswa');
            $table->foreign('id_siswa')
                  ->references('id_siswa')
                  ->on('siswa')
                  ->onDelete('cascade');

            // Kolom Status & Keterangan
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa']);
            $table->text('keterangan')->nullable();
            
            // Kolom File Bukti (Varchar 255)
            $table->string('file_bukti', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_presensi');
    }
};
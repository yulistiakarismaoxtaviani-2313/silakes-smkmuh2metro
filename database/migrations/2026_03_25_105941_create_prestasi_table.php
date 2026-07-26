<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestasiTable extends Migration
{
    public function up()
    {
       Schema::create('prestasi', function (Blueprint $table) {
    $table->id('id_prestasi');

    $table->unsignedBigInteger('id_siswa');
    $table->foreign('id_siswa')
          ->references('id_siswa')
          ->on('siswa')
          ->onDelete('cascade');

    $table->string('nama_prestasi');
    $table->string('cabang_lomba');
    $table->string('penyelenggara_lomba');
    $table->string('tingkat');
    $table->string('kategori');
    $table->string('peringkat');
    $table->date('tanggal');
    $table->string('file_bukti')->nullable();
    $table->string('status_validasi')->nullable();

    $table->unsignedBigInteger('divalidasi_oleh')->nullable();
    $table->foreign('divalidasi_oleh')
          ->references('id_user')
          ->on('users')
          ->nullOnDelete();

    $table->string('bebas_spp')->nullable();
    $table->text('keterangan')->nullable();
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('prestasi');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatKelasTable extends Migration
{
    public function up()
    {
       Schema::create('riwayat_kelas', function (Blueprint $table) {
    $table->id('id_riwayat');

    $table->unsignedBigInteger('id_siswa');
    $table->foreign('id_siswa')
          ->references('id_siswa')
          ->on('siswa')
          ->cascadeOnDelete();

    $table->unsignedBigInteger('id_kelas');
    $table->foreign('id_kelas')
          ->references('id_kelas')
          ->on('kelas');

    $table->unsignedBigInteger('id_tahun_ajaran');
    $table->foreign('id_tahun_ajaran')
          ->references('id_tahun_ajaran')
          ->on('tahun_ajaran');

    $table->unsignedBigInteger('id_semester');
    $table->foreign('id_semester')
          ->references('id_semester')
          ->on('semester');

    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_kelas');
    }
}